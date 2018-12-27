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
            this.$nextTick(() => {
                this.map = this.$refs.map.mapObject;

                if (this.field.value) {
                    L.geoJson(this.field.value).addTo(this.map);
                    this.map.fitBounds(L.geoJSON(this.field.value).getBounds());
                }
            });
        },
    }
</script>

<style scoped>
    @import "~leaflet/dist/leaflet.css";
</style>

