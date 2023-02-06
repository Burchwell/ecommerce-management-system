<template>
    <div id="filters">
        <b-row>
            <b-col>
                <b-row>
                    <b-col>
                        <b-form-input size="sm" v-model="filters['search']" :name="keys.search.key"
                                      :placeholder="keys.search.placeholder"/>
                    </b-col>
                </b-row>
            </b-col>
        </b-row>
        <b-row>
            <b-col v-for="(key, index) of keys.filters" cols="3" md="2" :key="index">
                <h5>{{ key.label }}</h5>
                <p v-if="key.description">{{ key.description }}</p>
                <template v-if="key.type === 'sort'">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" :id="`${key.key}_switch`"
                               :name="key.key" v-model="filters[key.key]">
                        <label class="custom-control-label" :for="`${key.key}_switch`">{{
                                filters[key.key] === true ? 'Descending' : 'Ascending'
                            }}</label>
                    </div>
                </template>
                <template v-if="key.type === 'radio'"></template>
                <template v-if="key.type === 'checkbox'"></template>
                <template v-if="key.type === 'switch'"></template>
                <template v-if="key.type === 'multiselect'"></template>

                <p class="small m-t-15" v-if="key.help">
                    {{ key.help }}
                </p>
            </b-col>
        </b-row>
    </div>
</template>

<script>
export default {
    name: "Filters",
    props: {
        keys: Object
    },
    data() {
        return {
            filters: {
                search: "",
            }
        }
    }
}
</script>

<style scoped>

</style>
