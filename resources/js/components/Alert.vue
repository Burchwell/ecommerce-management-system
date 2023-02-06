<template>
    <b-alert show variant="danger" :dismissible="dismissible" :fade="fade" :variant="variant" :title="title">
        <h4 v-if="title">{{title}}</h4>
        {{message}}
    </b-alert>
</template>

<script>
    export default {
        name: "Alert",
        props: {
            index: Number,
            fade: Boolean,
            dismissible: Boolean,
            variant: String,
            title: String,
            message: String
        },
        data() {
            return {
                show: true
            }
        },
        methods: {
            closeAlert() {
                this.show = false;
            }
        }
    }
</script>

<style scoped>

</style>
