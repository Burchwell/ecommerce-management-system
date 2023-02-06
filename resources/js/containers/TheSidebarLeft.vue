<template>
    <transition name="fade">
        <nav id="sidebarMenu" title="Sidebar" shadow class="sidebar">
            <div class="site-width">
                <template v-for="n of nav">
                    <ul v-if="n._name === 'SideBarNav'" class="sidebar-menu" id="side-menu">
                        <template v-for="snChild of n._children">
                            <li v-if="snChild._type === 'SidebarNavItem'">
                                <b-link :to="snChild.to" @click="toggleSidebarShow(false)" v-if="snChild.to">
                                    <i :class="snChild.icon" v-if="snChild.icon" class="float-left"/>
                                    {{ snChild.name }}
                                </b-link>
                                <b-link :href="snChild.href" @click="toggleSidebarShow(false)" v-if="snChild.href">
                                    <i :class="snChild.icon" v-if="snChild.icon" class="float-left"/>
                                    {{ snChild.name }}
                                </b-link>
                            </li>
                            <li v-if="snChild._type === 'SidebarNavDropdownItem'">
                                <b-link v-if="snChild.action" class="text-center text-info" :to="snChild.action.to">
                                    <i :class="snChild.icon" v-if="snChild.icon"
                                       class="float-left dropdown-icon text-info"/>
                                    {{ snChild.name }}
                                    <i :class="snChild.action.icon" class="float-right mt-1" v-if="snChild.action.icon"
                                       :title="snChild.action.text" v-b-tooltip:v-b-hover/>
                                </b-link>
                                <b-link v-else-if="snChild.to" :to="snChild.to" @click="toggleSidebarShow(false)"
                                        class="text-center text-info">
                                    <i :class="snChild.icon" v-if="snChild.icon"
                                       class="float-left dropdown-icon text-info"/>
                                    {{ snChild.name }}
                                </b-link>
                                <b-link :href="snChild.href" @click="toggleSidebarShow(false)"
                                        class="text-center text-info" v-else-if="snChild.href">
                                    <i :class="snChild.icon" v-if="snChild.icon"
                                       class="float-left dropdown-icon text-info"/>
                                    {{ snChild.name }}
                                </b-link>
                                <b-link @click="toggleSidebarShow(false)" class="text-center text-info" v-else>
                                    <i :class="snChild.icon" v-if="snChild.icon"
                                       class="float-left dropdown-icon text-info"/>
                                    {{ snChild.name }}
                                </b-link>
                                <ul>
                                    <template v-for="ddChild of snChild.children">
                                        <li class="navbar-text divider text-info" role="separator"
                                            v-if="ddChild._type === 'SidebarNavItemDivider'">
                                            <i :class="ddChild.icon" v-if="ddChild.icon" class="float-left"/>
                                            {{ ddChild.name }}
                                        </li>
                                        <li v-else>
                                            <b-link :to="ddChild.to" @click="toggleSidebarShow(false)"
                                                    v-if="ddChild.to">
                                                <i :class="ddChild.icon" v-if="ddChild.icon" class="float-left"/>
                                                {{ ddChild.name }}
                                            </b-link>
                                            <b-link :href="ddChild.href" @click="toggleSidebarShow(false)"
                                                    v-if="ddChild.href" target="_blank">
                                                <i :class="ddChild.icon" v-if="ddChild.icon" class="float-left"/>
                                                {{ ddChild.name }}
                                                <i class="fas fa-angle-double-right"/>
                                            </b-link>
                                        </li>
                                    </template>
                                </ul>
                            </li>

                        </template>
                    </ul>
                </template>
            </div>
        </nav>
    </transition>
</template>

<script>
import nav from './_nav'

export default {
    name: 'SideBarNav',
    nav,
    data() {
        return {
            nav: nav
        }
    },
    methods: {
        toggleSidebarShow(show) {
            let visible = typeof show !== 'undefined' ? show : !this.$store.state.sidebar.Left.Show;
            this.$store.state.sidebar.Left.Show = visible
        }
    }
}
</script>
