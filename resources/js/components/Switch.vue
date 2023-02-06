<template>
    <div class="form-check form-check-inline switch">
        <label v-if="Off" for="pagesSwitch">{{ Off }}</label>
        <input type="checkbox" id="pagesSwitch" :checked="Checked">
        <label for="pagesSwitch">{{ On }}</label>
    </div>
</template>

<script>
export default {
    name: "Switch",
    props: {
        On: {
            type: String,
            required: true
        },
        Off: {
            type: String,
        },
        Checked: {
            type: Boolean
        }
    }
}
</script>

<style scoped>

</style>
