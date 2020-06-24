<template>
    <div class="category" :style="{ backgroundColor: '#' + bgColor }">
        <div class="category_name">
            <i v-if="icon" :class="icon" class="category_icon"></i>
            {{ name }}
            <div class="action_links">
                <div class="action_edit">
                    <i class="fa fa-edit"></i>
                </div>
                <div class="action_add">
                    <i class="fa fa-plus"></i>
                </div>
            </div>
        </div>
        <div class="category_content">
            <div class="category_links" v-if="links.length > 0">
                <template v-for="link in links">
                    <div class="link">
                        <div class="link_favicon">
                            <img :src="link.favicon" alt="">
                        </div>
                        <div class="link_url">
                            <a :href="link.url" :title="link.description">{{ link.title }}</a>
                        </div>
                    </div>
                </template>
            </div>
            <div v-else>
                <i class="fa fa-plus"></i> Add your first Link
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'category',
        data () {
            return {

            }
        },
        computed: {},
        props: {
            name: {
                required: true,
                type: String
            },
            bgColor: {
                required: true,
                type: String
            },
            links: {
                required: true,
                type: Array
            },
            icon: {
                required: true,
                type: String
            }
        },
        components: {},
        methods: {},
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style scoped lang="scss">

    .category {
        flex-basis: 350px;
        flex-grow: 1;
        margin: 10px;
        border: solid 1px #dddddd;
    }

    .category_name {
        box-shadow: inset 0 0 400px 110px rgba(0, 0, 0, .2);
        padding: 10px 20px;
        color: #fff;
        font-weight: bold;
        font-size: medium;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category_icon {
        padding-right: 7px;
    }

    .category_content {
        padding: 10px;
    }

    .category_links {
        padding: 0 10px;
        font-size: medium;

        a {
            color: #000;
        }

    }

    .link {

        display: flex;
        line-height: 30px;

        .link_favicon {

            height: 16px;
            width: 16px;

            img {
                height: 16px;
                width: 16px;
            }

        }

        .link_url {
            display: flex;
            align-items: center;
            padding-left: 10px;
            text-decoration: underline;
        }

    }

    .action_links {

        display: flex;

        .action_add,
        .action_edit {
            box-shadow: inset 0 0 400px 110px rgba(0, 0, 0, .2);
            padding: 3px 7px;
            margin: 0 0 0 10px;
            font-size: small;
        }

    }

</style>
