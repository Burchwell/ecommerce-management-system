<template>
    <div id="header-fixed" class="header fixed-top">
        <div class="site-width">
            <nav class="navbar navbar-expand-lg p-0">
                <div class="navbar-header h4 h-100 mb-0 align-self-center logo-bar text-left" style="padding: 0">
                    <b-link class="horizontal-logo text-left" to="/">
                        <h1 data-v-65f0597f="" data-v-4f3b21ef="" style="
    font-size: 20px;
    margin-left: 115px;
"><img data-v-65f0597f="" data-v-4f3b21ef="" src="/images/skar-audio-logo-black.svg" width="75" style="
    margin-bottom: -15px;
    margin-left: -90px;
"> <br data-v-65f0597f="" data-v-4f3b21ef="">
                            Core
                        </h1>
                    </b-link>
                </div>
                <div class="navbar-header h4 mb-0 p-0 text-center h-100 collapse-menu-bar" :class="fixed">
                    <b-link @click="toggleSidebarLeft" class="sidebarCollapse" id="collapse">
                        <i class="fas fa-bars"></i>
                    </b-link>
                </div>
<!--                <form class="float-left d-none d-lg-block search-form">-->
<!--                    <div class="form-group mb-0 position-relative">-->
<!--                        <input type="text" class="form-control border-0 rounded bg-search pl-5" placeholder="Search anything...">-->
<!--                        <div class="btn-search position-absolute top-0">-->
<!--                            <a href="#"><i class="h6 icon-magnifier"></i></a>-->
<!--                        </div>-->
<!--                        <a href="#" class="position-absolute close-button mobilesearch d-lg-none" data-toggle="dropdown" aria-expanded="false"><i class="icon-close h5"></i>-->
<!--                        </a>-->

<!--                    </div>-->
<!--                </form>-->
                <div class="navbar-right ml-auto h-100">
                    <ul class="ml-auto p-0 m-0 list-unstyled d-flex top-icon h-100">
                        <li class="d-inline-block align-self-center  d-block">
                            <a href="#" class="nav-link mobilesearch" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-search"></i>
                            </a>
                        </li>

                        <li class="dropdown align-self-center">
                            <a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="false"><i class="icon-reload h4"></i>
                                <i class="fas fa-bell"/>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right border  py-0">
                                <li>
                                    <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0" href="#">
                                        <div class="media">
                                            <div class="media-body">
                                                <p class="mb-0">john</p>
                                                <span class="text-success">New user registered.</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0" href="#">
                                        <div class="media">
                                            <div class="media-body">
                                                <p class="mb-0">Peter</p>
                                                <span class="text-success">Server #12 overloaded.</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0" href="#">
                                        <div class="media">
                                            <div class="media-body">
                                                <p class="mb-0">Bill</p>
                                                <span class="text-danger">Application error.</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>

                                <li><a class="dropdown-item text-center py-2" href="#"> See All Tasks <i class="icon-arrow-right pl-2 small"></i></a></li>
                            </ul>

                        </li>
                        <li class="dropdown align-self-center d-inline-block">
                            <a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"/>
                                <span class="badge badge-default"/>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right border   py-0">
                                <li>
                                    <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0" href="#">
                                        <div class="media">
                                            <img src="dist/images/author.jpg" alt="" class="d-flex mr-3 img-fluid rounded-circle w-50">
                                            <div class="media-body">
                                                <p class="mb-0 text-success">john send a message</p>
                                                12 min ago
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0" href="#">
                                        <div class="media">
                                            <img src="dist/images/author2.jpg" alt="" class="d-flex mr-3 img-fluid rounded-circle">
                                            <div class="media-body">
                                                <p class="mb-0 text-danger">Peter send a message</p>
                                                15 min ago
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0" href="#">
                                        <div class="media">
                                            <img src="dist/images/author3.jpg" alt="" class="d-flex mr-3 img-fluid rounded-circle">
                                            <div class="media-body">
                                                <p class="mb-0 text-warning">Bill send a message</p>
                                                5 min ago
                                            </div>
                                        </div>
                                    </a>
                                </li>

                                <li><a class="dropdown-item text-center py-2" href="#"> Read All Message <i class="icon-arrow-right pl-2 small"></i></a></li>
                            </ul>
                        </li>
                        <li class="dropdown user-profile align-self-center d-inline-block">
                            <b-link to="/admin" class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cog"/>
                                <span class="badge badge-default"/>
                            </b-link>
                        </li>
                        <li class="dropdown user-profile align-self-center d-inline-block">
                            <b-link @click="logout" class="dropdown-item px-2 py-2 border border-top-0 border-left-0 border-right-0" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-power-off"/>
                                <span class="badge badge-default"/>
                            </b-link>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'TheHeader',
        components: {},
        computed: {
            fixed() {
                return this.$store.state.sidebar.Left.Fixed ? 'd-lg-none' : '';
            }
        },
        data() {
            return {
                user: {}
            }
        },
        methods: {
            toggleSidebarLeft() {
                this.$store.state.sidebar.Left.Show = !this.$store.state.sidebar.Left.Show;
            },
            logout() {
                localStorage.removeItem('user');
                localStorage.removeItem('access_token');
                this.$router.push({ path: 'login' })
            }
        },
        mounted() {
            this.user = JSON.parse(localStorage.getItem('user'));
        }
    }
</script>

<style scoped lang="scss">

</style>
