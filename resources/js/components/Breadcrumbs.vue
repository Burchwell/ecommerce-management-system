<template>
    <b-row class="bg-white">
        <b-col cols="12" class="align-self-center">
            <div class="sub-header mt-3 p-3 align-self-center d-sm-flex w-100 rounded">
                <div class="mt-sm-2">
                    <b-breadcrumb class="bg-transparent align-self-center m-0 p-0">
                        <b-breadcrumb-item to="/">
                            <span class="text-muted">Home</span>
                        </b-breadcrumb-item>
                        <template v-for="(crumb, index) of crumbs">
                            <b-breadcrumb-item :to="crumb.to" v-if="index !== (crumbs.length - 1)">
                                <span class="text-muted">{{
                                        format(crumb.path.charAt(0).toUpperCase() + crumb.path.slice(1))
                                    }}</span>
                            </b-breadcrumb-item>
                            <b-breadcrumb-item v-else active>
                                {{ format(crumb.path.charAt(0).toUpperCase() + crumb.path.slice(1)) }}
                            </b-breadcrumb-item>
                        </template>
                    </b-breadcrumb>
                </div>
            </div>
        </b-col>
    </b-row>
</template>

<script>
export default {
    name: "Breadcrumbs",
    props: {
        crumbs: Array
    },
    data() {
        return {

        }
    },
    methods: {
        format(path) {
            let string = path.charAt(0).toUpperCase() + path.slice(1);
            return string.replace('_', ' ', string);
        }
    }
}
</script>

<style scoped>

</style>
