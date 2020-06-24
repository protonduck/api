<template>
    <div class="board" :endpoint="endpoint">

        <div class="board_names">
            <template v-if="boards.length > 0" v-for="(board, index) in boards">
                <a href="#" @click.prevent="changeBoard(index)" class="board_name" v-bind:class="{ active: boardId === index }">
                    {{ board.name }}
                </a>
            </template>
            <div class="board_name">+Add</div>
        </div>

        <div class="board_categories">
            <template v-for="category in categories">
                <category-component :name="category.name"
                                    :bg-color="category.color"
                                    :icon="category.icon"
                                    :links="category.links"
                ></category-component>
            </template>
            <div class="category_add">
                   +Add category
            </div>
        </div>
    </div>
</template>

<script>

    import categoryComponent from './CategoryCompontent';
    import axios from 'axios';

    export default {
        name: 'board',
        data () {
            return {
                boards: [],
                categories: [],
                boardId: 0,
            }
        },
        computed: {},
        props: {
            endpoint: {
                required: true,
                type: String
            },
        },
        components: {
            categoryComponent,
        },
        methods: {
            fetchData() {
                return axios.get(`${this.endpoint}`)
            },
            async loadBoards() {

                let boards = await this.fetchData();

                this.boards = boards.data;

                await this.loadCategories();

            },
            async loadCategories() {

                let board = this.boards[this.boardId];

                this.categories = board.categories;

                // add bg to body
                document.body.style.backgroundImage = "url('" + board.image + "')";
                document.body.className = 'body_bg_image';

            },
            changeBoard(id) {
                this.boardId = id;
                this.loadCategories();
            }
        },
        created() {
            this.loadBoards()
        },
        mounted() {
        }
    }
</script>

<style scoped lang="scss">

    .board_names {
        display: flex;
        box-shadow: inset 0 0 400px 110px rgba(0, 0, 0, .4);
        color: #fff;
        padding: 10px;
        margin: 20px 10px;
        border-radius: 10px;
    }

    .board_name {
        font-weight: bold;
        font-size: larger;
        padding: 3px 20px;
        margin: 3px 5px;
        color: #fff;
    }

    .active,
    .board_name:hover {
        box-shadow: inset 0 0 400px 110px rgba(0, 0, 0, .4);
        border-radius: 5px;
        cursor: pointer;
    }

    .board_categories {
        display: flex;
        flex-wrap: wrap
    }

    .category_add {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-basis: 450px;
        flex-grow: 1;
        margin: 10px;
        box-shadow: inset 0 0 400px 110px rgba(0, 0, 0, .4);
        color: #fff;
        font-weight: bold;
        font-size: larger;
        padding: 3px 20px;
    }

</style>
