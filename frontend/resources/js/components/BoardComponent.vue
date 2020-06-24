<template>
    <div class="board" :endpoint="endpoint">

        <div class="board_names">
            <template v-if="boards.length > 0" v-for="(board, index) in boards">
                <a href="#" @click.prevent="changeBoard(index)" class="board_name" v-bind:class="{ active: boardId === index }" v-if="!showBoardAddForm">
                    {{ board.name }}
                </a>
            </template>
            <div class="board_name active" @click.prevent="showBoardAddForm = true" v-if="!showBoardAddForm">
                <i class="fa fa-plus"></i>
            </div>
            <div class="board_form" v-if="showBoardAddForm">
                <form @submit.prevent="store">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="board_form_input" v-model="form.name"
                           autofocus="autofocus" placeholder="Private" :class="{'board_form_error': errorMessage}" required>

                    <label for="image">Image URL</label>
                    <input type="text" id="image" name="image" class="board_form_input" v-model="form.image" placeholder="https://">

                    <input type="submit" value="Save" class="board_form_button_save">
                    <a href="#" @click.prevent="showBoardAddForm = false" class="board_form_button_close">Close</a>
                </form>
            </div>
            <div class="board_name active" v-if="!showBoardAddForm">
                <i class="fa fa-edit"></i>
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
    import axios from 'axios';
    import bus from './../bus';

    export default {
        name: 'board',
        data () {
            return {
                boards: [],
                categories: [],
                boardId: 0,
                showBoardAddForm: false,
                form: {
                    name: '',
                    image: ''
                },
                errorMessage: ''
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
            async prependBoard (board) {
                this.boards.push(board)
            },
            changeBoard(id) {
                this.boardId = id;
                this.loadCategories();
            },
            async store () {

                await axios.post(this.endpoint, this.form).then(response => {

                    bus.$emit('board:stored', response.data);

                    this.showBoardAddForm = false;
                    this.form.name = '';
                    this.form.image = '';
                    this.errorMessage = '';

                }).catch(error => {
                    this.errorMessage = error.message;
                });

            }
        },
        created() {
            this.loadBoards()
        },
        mounted() {

            bus.$on('board:stored', this.prependBoard);

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

    .board_form {
        display: flex;
        align-items: center;

        label {
            margin: 0 15px;
        }
    }

    .board_form_input {
        color: #333333;
        padding: 3px;
        margin: 4px;
        border-radius: 5px;
        border: none;
    }

    .board_form_button_save {
        box-shadow: inset 0 0 400px 110px rgba(0, 0, 0, .2);
        background: rgba(0, 0, 0, .4);
        border-radius: 5px;
        cursor: pointer;
        color: #fff;
        font-weight: bold;
        border: none;
        padding: 5px 10px;
        margin: 0 10px;
    }

    .board_form_error {
        background-color: #F2DEDE;
        border: 1px solid #EED3D7;
    }

    .board_form_button_close {
        color: #fff;
        margin: 0 10px;
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
