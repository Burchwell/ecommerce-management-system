<template>
    <b-row>
        <div class="card card-transparent">
            <div class="card-header">
                <b-row>
                    <b-col>
                        <div class="card-title">Dashboard
                        </div>
                        <div class="card-controls float-right">
                            <ul style="list-style: none">
                                <li><a href="#" class="card-refresh" data-toggle="refresh"><i class="fas fa-redo-alt"/></a>
                                </li>
                            </ul>
                        </div>
                    </b-col>
                </b-row>
            </div>
            <div class="card-body d-flex flex-wrap">
                <div class="col-lg-4 no-padding">
                    <div class="p-r-30">
                        <h3>Product Sales by Days</h3>
                        <br>
                        <p>This project is an attempt to build re-usable charts and chart components for d3.js without
                            taking away the power that d3.js gives you. This is a very young collection of components,
                            with the goal of keeping these components very customizeable, staying away from your
                            standard cookie cutter solutions.</p>
                        <br>
                    </div>
                </div>
                <div class="col-lg-8 sm-no-padding">

                </div>
            </div>
        </div>
    </b-row>
</template>

<script>
export default {
    name: 'Dashboard',
    data() {
        return {}
    },
    computed: {},
    methods: {}
}
</script>
