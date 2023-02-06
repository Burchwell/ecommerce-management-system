<template>
    <div id="app" v-bind:class="show">
        <Loading v-if="$store.state.loading"/>

        <TheSidebarLeft/>
        <main>
            <TheHeader/>
            <b-container fluid>
                <Breadcrumbs :crumbs="crumbs" />
                <b-row class="pb-4">
                    <b-col>
                        <router-view></router-view>
                    </b-col>
                </b-row>
            </b-container>
        </main>
    </div>
</template>

<script>
import TheSidebarLeft from './TheSidebarLeft'
import TheSidebarRight from './TheSidebarRight'
import TheHeader from './TheHeader'
import TheFooter from './TheFooter'
import Loading from "../components/Loading";
import Breadcrumbs from "../components/Breadcrumbs";

export default {
    name: 'TheContainer',
    components: {
        Breadcrumbs,
        Loading,
        TheSidebarLeft,
        TheSidebarRight,
        TheHeader,
        TheFooter,
    },
    computed: {
        show() {
            if (this.$store.state.sidebar.Left.Fixed) {
                return this.$store.state.sidebar.Left.Show ? 'fixed' : 'fixed sidebar-hidden';
            }
            return this.$store.state.sidebar.Left.Show ? '' : 'sidebar-hidden';
        },
        crumbs: function () {
            let pathArray = this.$route.path.split("/")
            pathArray.shift()
            let breadcrumbs = pathArray.reduce((breadcrumbArray, path, idx) => {
                breadcrumbArray.push({
                    path: path,
                    to: breadcrumbArray[idx - 1]
                        ? "/" + breadcrumbArray[idx - 1].path + "/" + path
                        : "/" + path
                });
                return breadcrumbArray;
            }, [])
            return breadcrumbs;
        }
    },
    data() {
        return {
            alerts: [
            //     {
            //         dismissible: true,
            //         fade: true,
            //         variant: "success",
            //         title: "Title",
            //         message: "Test Message"
            //     },
            //     {
            //         dismissible: true,
            //         fade: false,
            //         variant: "info",
            //         title: "Title",
            //         message: "Test Message"
            //     },
            //     {
            //         dismissible: true,
            //         fade: true,
            //         variant: "warning",
            //         title: "Title",
            //         message: "Test Message"
            //     },
            //     {
            //         dismissible: false,
            //         fade: true,
            //         variant: "danger",
            //         message: "Test Message"
            //     },
            ]
        }
    }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.alert-dismissible .close {
    padding: 0.5rem 125rem !important;
}
</style>
