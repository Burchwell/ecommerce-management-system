<template>
    <b-modal id="productSkuMappings" class="p-0" static="true" no-close-on-backdrop>
        <template v-slot:modal-header="{ close }">
            <h5>{{ product.sku }} Sku Mappings</h5>
            <b-link class="text-black float-right" @click="close()">
                <em class="fal fa-times"/>
            </b-link>
        </template>
        <template v-slot:default class="p-0">
            <b-table
                :fields="columns"
                :items="skuMapping">
                <template v-slot:head(id)="data">
                    <b-form-checkbox v-model="selectAll" @click="selectAll"/>
                </template>
                <template v-slot:cell(id)="data">
                    <b-form-checkbox name="product" v-model="selected"
                                     :value="JSON.stringify(skuMapping.item)"/>
                </template>
                <template v-slot:cell(mapping)="data">
                    <b-form-input type="text" v-model="skuMapping[data.index].mapping" placeholder="Sku"/>
                </template>
                <template v-slot:cell(source)="data">
                    <b-form-select v-model="data.item.source">
                        <b-form-select-option value="">All</b-form-select-option>
                        <b-form-select-option value="Amazon">Amazon</b-form-select-option>
                        <b-select-option value="Shopify" selected>Shopify</b-select-option>
                        <b-select-option value="Quickbooks" selected>Quickbooks</b-select-option>
                    </b-form-select>
                </template>
                <template v-slot:head(option)="data">
                    <em class="fad fa-cog"/>
                </template>
                <template v-slot:cell(option)="data">
                    <b-button-group>
                        <b-button type="button" variant="link" class="text-danger mt-1"
                                  @click="deleteSkuMapping(data.index)">
                            <em class="fas fa-times"/>
                        </b-button>
                    </b-button-group>
                </template>
            </b-table>
        </template>
        <template v-slot:modal-footer="{ ok, cancel}">
            <b-button size="sm" variant="secondary" class="position-absolute mx-4" style="left: 0" @click="createSkuMapping">
                <em class="fad fa-plus-square-o"/> New Mapping
            </b-button>
            <!-- Emulate built in modal footer ok and cancel button actions -->
            <b-button size="sm" variant="success" @click="saveSkuMapping()">
                <em class="fad fa-save mr-2"/> Save
            </b-button>
            <b-button size="sm" variant="danger" @click="cancel()">
                <em class="fad fa-ban mr-2"/> Cancel
            </b-button>
            <!-- Button with custom close trigger value -->
        </template>
    </b-modal>
</template>

<script>
import api from "../../api";

export default {
    name: "SkuMappingsModal",
    props: {
        product: Object,
        skuMapping: Array,
        show: Boolean
    },
    watch: {
        changes: function () {
            this.close
        }
    },
    computed: {
        selectAll: {
            get() {
                return this.skuMapping ? this.selected.length === this.skuMapping.length : false;
            },
            set(value) {
                var selected = [];
                if (value) {
                    this.skuMapping.forEach(function (product) {
                        selected.push(JSON.stringify(product));
                    });
                }
                this.selected = selected;
            }
        },
    },
    data() {
        return {
            changes: false,
            columns: [
                {key: 'id', label: "ID", class: 'd-none d-md-table-cell bg-gray-50', sortable: true},
                {key: 'mapping', label: "Mapping", class: 'd-none d-md-table-cell bg-gray-50', sortable: true},
                {key: 'source', label: "Source", class: 'd-none d-md-table-cell bg-gray-50', sortable: true},
                {key: 'option', label: "", class: 'd-none d-md-table-cell bg-gray-50 text-center'},
            ],
            selected: []
        }
    },
    methods: {
        createSkuMapping() {
            this.skuMapping.push({mapping: '', source: ''})
        },
        async saveSkuMapping() {
            try {
                const response = await api.post(`/products/${this.product.id}/mappings`, this.skuMapping)
                this.$emit('skuMappingSaved', response.data.mappings, this.product.id);
                this.$bvModal.hide('productSkuMappings')
            } catch (e) {
                throw new Error(e)
            }
        },
        async deleteSkuMapping(index) {
            try {
                const response = await api.delete(`/products/${this.skuMapping[index].product_id}/mappings/${this.skuMapping[index].id}`)
                if (response.data.deleted === 1) {
                    this.skuMapping.splice(index, 1);
                }
            } catch (e) {
                throw new Error(e)
            }
        },
        close() {
            this.$emit('close');
        }
    }
}
</script>

<style scoped>

</style>
