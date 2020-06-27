<template>
    <div class="board" :endpoint="endpoint" :accesstoken="accesstoken">

        <div class="board_names">
            <template v-if="boards.length > 0" v-for="(board, index) in boards">
                <a href="#" @click.prevent="changeBoard(index)" class="board_name" v-bind:class="{ active: boardId === index }" :id="`board-${board.id}`">
                    {{ board.name }}
                </a>
            </template>
            <board-add-component :endpoint="endpoint" :accesstoken="accesstoken"></board-add-component>
            <board-edit-component :endpoint="endpoint" :accesstoken="accesstoken" :board="board"></board-edit-component>
            <div class="board_buttons active" @click.prevent="destroy">
                <i class="fa fa-trash"></i>
            </div>
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
    import boardAddComponent from './BoardAddComponent';
    import boardEditComponent from './BoardEditComponent';
    import axios from 'axios';
    import bus from './../bus';
    import _ from 'lodash';

    export default {
        name: 'board',
        data () {
            return {
                boards: [],
                board: {},
                categories: [],
                boardId: 0,
                editing: false
            }
        },
        computed: {},
        props: {
            endpoint: {
                required: true,
                type: String
            },
            accesstoken: {
                required: true,
                type: String
            },
        },
        components: {
            categoryComponent,
            boardAddComponent,
            boardEditComponent
        },
        methods: {
            fetchData() {
                return axios.get(`${this.endpoint}` + '?access-token=' +  `${this.accesstoken}`)
            },
            async loadBoards() {

                let boards = await this.fetchData();

                this.boards = boards.data;

                await this.loadCategories();

            },
            async loadCategories() {

                let board = this.boards[this.boardId];

                this.board = board;

                this.categories = board.categories;

                // add bg to body
                document.body.style.backgroundImage = "url('" + board.image + "')";
                document.body.className = 'body_bg_image';

            },
            async prependBoard (board) {
                this.boards.push(board)
            },
            changeBoard(id) {
                this.boardId = id;
                this.loadCategories();
            },
            editBoard(boards){
                _.assign(_.find(this.boards, {id: boards.id}), boards);
            },
            async destroy () {

                if (confirm('Are you sure?')) {
                    await axios.delete(`${this.endpoint}/${this.board.id}?access-token=${this.accesstoken}`);
                    bus.$emit('board:deleted', this.board);
                }

            },
            deleteBoard(board) {
                this.boards = this.boards.filter((b) => b.id !== board.id);
                this.changeBoard(0);
            }
        },
        created() {
            this.loadBoards()
        },
        mounted() {

            bus.$on('board:stored', this.prependBoard);

            bus.$on('board:edited', this.editBoard);

            bus.$on('board:edit-cancelled', (board) => {

                if (board.id === this.board.id) {
                    this.editing = false
                }

            })

            bus.$on('board:add-cancelled', () => {});

            bus.$on('board:deleted', this.deleteBoard);

        }
    }
</script>

<style scoped lang="scss">

    .board_names {
        display: flex;
        flex-wrap: wrap;
        box-shadow: inset 0 0 400px 110px rgba(0, 0, 0, .4);
        color: #fff;
        padding: 10px;
        margin: 20px 10px;
        border-radius: 10px;
    }

    .board_name,
    .board_buttons {
        font-weight: bold;
        font-size: larger;
        padding: 3px 20px;
        margin: 3px 5px;
        color: #fff;
    }

    .active,
    .board_name:hover,
    .board_buttons {
        box-shadow: inset 0 0 400px 110px rgba(0, 0, 0, .4);
        border-radius: 5px;
        cursor: pointer;
    }

    .board_buttons {
        border-radius: 0;
        padding-left: 7px;
        padding-right: 7px;
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
