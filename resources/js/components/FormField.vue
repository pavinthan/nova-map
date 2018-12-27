<template>
    <default-field :field="field" :errors="errors" :fullWidthContent="true">
        <template slot="field">
            <div class="rounded flex flex-col w-full container-height" :style="{ height: `${field.height}px` }">
                <l-map
                    class="w-full h-full rounded z-10"
                    ref="map"
                    :center="center"
                    :zoom="field.value ? field.zoom : 0"
                >
                    <l-tile-layer
                        v-for="tileProvider in tileProviders"
                        layerType="base"
                        :visible="tileProvider.visible"
                        :url="tileProvider.url"
                        :key="tileProvider.url"
                    />
                </l-map>
            </div>
        </template>
    </default-field>
</template>

<script>
    import {L, LMap, LTileLayer} from 'vue2-leaflet'
    import {FormField, HandlesValidationErrors} from 'laravel-nova'
    // Apply plugin
    import 'leaflet.pm'

    // Fix image path
    const DefaultIcon = L.icon({
        iconUrl: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAApCAYAAADAk4LOAAAFgUlEQVR4Aa1XA5BjWRTN2oW17d3YaZtr2962HUzbDNpjszW24mRt28p47v7zq/bXZtrp/lWnXr337j3nPCe85NcypgSFdugCpW5YoDAMRaIMqRi6aKq5E3YqDQO3qAwjVWrD8Ncq/RBpykd8oZUb/kaJutow8r1aP9II0WmLKLIsJyv1w/kqw9Ch2MYdB++12Onxee/QMwvf4/Dk/Lfp/i4nxTXtOoQ4pW5Aj7wpici1A9erdAN2OH64x8OSP9j3Ft3b7aWkTg/Fm91siTra0f9on5sQr9INejH6CUUUpavjFNq1B+Oadhxmnfa8RfEmN8VNAsQhPqF55xHkMzz3jSmChWU6f7/XZKNH+9+hBLOHYozuKQPxyMPUKkrX/K0uWnfFaJGS1QPRtZsOPtr3NsW0uyh6NNCOkU3Yz+bXbT3I8G3xE5EXLXtCXbbqwCO9zPQYPRTZ5vIDXD7U+w7rFDEoUUf7ibHIR4y6bLVPXrz8JVZEql13trxwue/uDivd3fkWRbS6/IA2bID4uk0UpF1N8qLlbBlXs4Ee7HLTfV1j54APvODnSfOWBqtKVvjgLKzF5YdEk5ewRkGlK0i33Eofffc7HT56jD7/6U+qH3Cx7SBLNntH5YIPvODnyfIXZYRVDPqgHtLs5ABHD3YzLuespb7t79FY34DjMwrVrcTuwlT55YMPvOBnRrJ4VXTdNnYug5ucHLBjEpt30701A3Ts+HEa73u6dT3FNWwflY86eMHPk+Yu+i6pzUpRrW7SNDg5JHR4KapmM5Wv2E8Tfcb1HoqqHMHU+uWDD7zg54mz5/2BSnizi9T1Dg4QQXLToGNCkb6tb1NU+QAlGr1++eADrzhn/u8Q2YZhQVlZ5+CAOtqfbhmaUCS1ezNFVm2imDbPmPng5wmz+gwh+oHDce0eUtQ6OGDIyR0uUhUsoO3vfDmmgOezH0mZN59x7MBi++WDL1g/eEiU3avlidO671bkLfwbw5XV2P8Pzo0ydy4t2/0eu33xYSOMOD8hTf4CrBtGMSoXfPLchX+J0ruSePw3LZeK0juPJbYzrhkH0io7B3k164hiGvawhOKMLkrQLyVpZg8rHFW7E2uHOL888IBPlNZ1FPzstSJM694fWr6RwpvcJK60+0HCILTBzZLFNdtAzJaohze60T8qBzyh5ZuOg5e7uwQppofEmf2++DYvmySqGBuKaicF1blQjhuHdvCIMvp8whTTfZzI7RldpwtSzL+F1+wkdZ2TBOW2gIF88PBTzD/gpeREAMEbxnJcaJHNHrpzji0gQCS6hdkEeYt9DF/2qPcEC8RM28Hwmr3sdNyht00byAut2k3gufWNtgtOEOFGUwcXWNDbdNbpgBGxEvKkOQsxivJx33iow0Vw5S6SVTrpVq11ysA2Rp7gTfPfktc6zhtXBBC+adRLshf6sG2RfHPZ5EAc4sVZ83yCN00Fk/4kggu40ZTvIEm5g24qtU4KjBrx/BTTH8ifVASAG7gKrnWxJDcU7x8X6Ecczhm3o6YicvsLXWfh3Ch1W0k8x0nXF+0fFxgt4phz8QvypiwCCFKMqXCnqXExjq10beH+UUA7+nG6mdG/Pu0f3LgFcGrl2s0kNNjpmoJ9o4B29CMO8dMT4Q5ox8uitF6fqsrJOr8qnwNbRzv6hSnG5wP+64C7h9lp30hKNtKdWjtdkbuPA19nJ7Tz3zR/ibgARbhb4AlhavcBebmTHcFl2fvYEnW0ox9xMxKBS8btJ+KiEbq9zA4RthQXDhPa0T9TEe69gWupwc6uBUphquXgf+/FrIjweHQS4/pduMe5ERUMHUd9xv8ZR98CxkS4F2n3EUrUZ10EYNw7BWm9x1GiPssi3GgiGRDKWRYZfXlON+dfNbM+GgIwYdwAAAAASUVORK5CYII=",
        shadowUrl: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACkAAAApCAQAAAACach9AAACMUlEQVR4Ae3ShY7jQBAE0Aoz/f9/HTMzhg1zrdKUrJbdx+Kd2nD8VNudfsL/Th///dyQN2TH6f3y/BGpC379rV+S+qqetBOxImNQXL8JCAr2V4iMQXHGNJxeCfZXhSRBcQMfvkOWUdtfzlLgAENmZDcmo2TVmt8OSM2eXxBp3DjHSMFutqS7SbmemzBiR+xpKCNUIRkdkkYxhAkyGoBvyQFEJEefwSmmvBfJuJ6aKqKWnAkvGZOaZXTUgFqYULWNSHUckZuR1HIIimUExutRxwzOLROIG4vKmCKQt364mIlhSyzAf1m9lHZHJZrlAOMMztRRiKimp/rpdJDc9Awry5xTZCte7FHtuS8wJgeYGrex28xNTd086Dik7vUMscQOa8y4DoGtCCSkAKlNwpgNtphjrC6MIHUkR6YWxxs6Sc5xqn222mmCRFzIt8lEdKx+ikCtg91qS2WpwVfBelJCiQJwvzixfI9cxZQWgiSJelKnwBElKYtDOb2MFbhmUigbReQBV0Cg4+qMXSxXSyGUn4UbF8l+7qdSGnTC0XLCmahIgUHLhLOhpVCtw4CzYXvLQWQbJNmxoCsOKAxSgBJno75avolkRw8iIAFcsdc02e9iyCd8tHwmeSSoKTowIgvscSGZUOA7PuCN5b2BX9mQM7S0wYhMNU74zgsPBj3HU7wguAfnxxjFQGBE6pwN+GjME9zHY7zGp8wVxMShYX9NXvEWD3HbwJf4giO4CFIQxXScH1/TM+04kkBiAAAAAElFTkSuQmCC"
    });

    L.Marker.prototype.options.icon = DefaultIcon;

    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        components: {
            LMap,
            LTileLayer,
        },

        data() {
            return {
                map: null,
                layers: [],
                center: [0, 0],
                tileProviders: [{
                    visible: true,
                    url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
                }]
            }
        },

        mounted() {
            this.$nextTick(() => {
                this.map = this.$refs.map.mapObject;

                this.initValue();
                this.setCenter();
                this.applyToolbar();
                this.listenEvents();
            });
        },

        methods: {
            /*
             * Set the initial layer.
             */
            initValue() {
                if (this.field.value) {
                    const initialLayer = L.geoJson(this.field.value).addTo(this.map);
                    this.layers = this.layers.concat(Object.values(initialLayer._layers));
                }
            },

            /**
             * Set draw point to center.
             */
            setCenter() {
                if (this.field.value) {
                    this.map.fitBounds(L.geoJSON(this.field.value).getBounds());
                }
            },

            /**
             * Apply leaflet.pm Toolbar.
             */
            applyToolbar() {
                this.map.pm.addControls({
                    position: 'topleft', // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
                    useFontAwesome: false, // use fontawesome instead of geomanIcons (you need to include fontawesome yourself)
                    drawMarker: true, // adds button to draw markers
                    drawPolyline: true, // adds button to draw a polyline
                    drawRectangle: true, // adds button to draw a rectangle
                    drawPolygon: true, // adds button to draw a polygon
                    drawCircle: true, // adds button to draw a cricle
                    cutPolygon: true, // adds button to cut a hole in a polygon
                    editMode: true, // adds button to toggle edit mode for all layers
                    removalMode: true, // adds a button to remove layers
                });
            },

            /**
             * Listen all events.
             */
            listenEvents() {
                // listen to when a new layer is created
                this.map.on('pm:create', this.handleCreateLayer);

                // listen to when drawing mode gets enabled
                this.map.on('pm:remove', this.handleRemoveLayer);

                // listen to when drawing mode gets enabled
                this.map.on('pm:drawstart', this.handleChange);

                // listen to when drawing mode gets disabled
                this.map.on('pm:drawend', this.handleChange);
            },

            /**
             * Fill the given FormData object with the field's internal value.
             */
            fill(formData) {
                // Create form value
                const featureCollection = {
                    "type": "FeatureCollection",
                    "features": []
                };

                this.layers.forEach(layer => {
                    featureCollection.features.push(layer.toGeoJSON());
                });

                // Append into form data
                formData.append(this.field.attribute, JSON.stringify(featureCollection));
            },

            /**
             * Update the field's internal value
             */
            handleChange() {
                // Create form value
                const featureCollection = {
                    "type": "FeatureCollection",
                    "features": []
                };

                this.layers.forEach(layer => {
                    featureCollection.features.push(layer.toGeoJSON());
                });

                this.value = JSON.stringify(featureCollection);
            },

            /**
             * Update the field's internal value
             */
            handleCreateLayer(event) {
                this.layers.push(event.layer);
            },

            /**
             * Update the field's internal value
             */
            handleRemoveLayer(event) {
                const layerIndex = this.layers.findIndex(i => i._leaflet_id === event.layer._leaflet_id);
                this.layers.splice(layerIndex, 1);
            },
        },
    }
</script>

<style scoped>
    @import "~leaflet/dist/leaflet.css";
    @import "~leaflet.pm/dist/leaflet.pm.css";
</style>
