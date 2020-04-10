<template>
    <panel-item :field="field">
        <template slot="value">
            <div class="rounded flex flex-col w-full" :style="{ height: `${field.height}px` }">
                <l-map
                    class="w-full h-full rounded z-10"
                    ref="map"
                    :center="center"
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
    </panel-item>
</template>

<script>
    import {L, LMap, LTileLayer} from 'vue2-leaflet'
    import {FormField, HandlesValidationErrors} from 'laravel-nova'

    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: ['resource', 'resourceName', 'resourceId', 'field'],

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
            /**
             * Convert the value to valid json object
             * We expect PHP to store the data as stringified JSON object.
             */
            let convertValue = function (json) {
                if (typeof json === 'object') {
                    return json;
                }

                try {
                    return JSON.parse(json);
                }
                catch(e) {
                    return {}
                }
            };

            this.$nextTick(() => {
                this.map = this.$refs.map.mapObject;

                // Fit Bounding
                if (this.field.value) {
                    L.geoJson(convertValue(this.field.value)).addTo(this.map);
                    this.map.fitBounds(L.geoJSON(convertValue(this.field.value)).getBounds());
                }

                // Apply the user configured zoom, can be from the field zoom method or config file as fallback
                if (this.field.zoom) {
                    this.map.setZoom(this.field.zoom);
                }
            });
        },
    }
</script>

<style scoped>
    @import "~leaflet/dist/leaflet.css";
</style>

