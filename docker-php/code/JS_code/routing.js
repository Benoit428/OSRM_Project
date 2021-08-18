L.Routing.OSRMTrip = L.Routing.OSRM.extend({

    _routeDone: function(response, inputWaypoints, callback, context) {
        var coordinates,
            alts,
            actualWaypoints,
            i;

        if (response.trips)
            response = response.trips[0];

        context = context || callback;
        if (response.status !== 0) {
            callback.call(context, {
                status: response.status,
                message: response.status_message
            });
            return;
        }

        coordinates = this._decodePolyline(response.route_geometry);
        actualWaypoints = this._toWaypoints(inputWaypoints, response.via_points, response.permutation);
        alts = [{
            name: this._createName(response.route_name),
            coordinates: coordinates,
            instructions: response.route_instructions ? this._convertInstructions(response.route_instructions) : [],
            summary: response.route_summary ? this._convertSummary(response.route_summary) : [],
            inputWaypoints: inputWaypoints,
            waypoints: actualWaypoints,
            waypointIndices: this._clampIndices(response.via_indices, coordinates)
        }];
        callback.call(context, null, alts);
    },

    _toWaypoints: function(inputWaypoints, vias, permutation) {
        var wps = [],
            i, j;
        for (i = 0; i < permutation.length; i++) {
            for (j = 0; j < inputWaypoints.length; j++) {
                if (permutation[i] == j) {
                    wps.push(L.Routing.waypoint(L.latLng(vias[i]),
                        inputWaypoints[j].name,
                        inputWaypoints[j].options));
                }
            }
        }

        return wps;
    },

    buildRouteUrl: function(waypoints, options) {
        var locs = [],
            wp,
            computeInstructions,
            locationKey,
            hint;

        for (var i = 0; i < waypoints.length; i++) {
            wp = waypoints[i];
            locationKey = this._locationKey(wp.latLng);
            locs.push('loc=' + locationKey);

            hint = this._hints.locations[locationKey];
            if (hint) {
                locs.push('hint=' + hint);
            }

            if (wp.options && wp.options.allowUTurn) {
                locs.push('u=true');
            }
        }

        computeInstructions = !(options && options.geometryOnly);

        return this.options.serviceUrl + '?' +
            'instructions=' + computeInstructions.toString() + '&' +
            (options.z ? 'z=' + options.z + '&' : '') +
            locs.join('&') +
            (this._hints.checksum !== undefined ? '&checksum=' + this._hints.checksum : '') +
            (options.fileformat ? '&output=' + options.fileformat : '') +
            (options.allowUTurns ? '&uturns=' + options.allowUTurns : '');
    },
});

L.Routing.osrmTrip = function(options) {
    return new L.Routing.OSRMTrip(options);
};