<template>
    <div>
        <div class="board_buttons" @click.prevent="boardEditing" v-if="!showBoardForm">
            <i class="fa fa-edit"></i>
        </div>
        <div class="board_form" v-if="showBoardForm">
            <form @submit.prevent="patch">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="board_form_input" v-model="form.name"
                       autofocus="autofocus" :class="{'board_form_error': errorMessage}" required />

                <label for="image">Image URL</label>
                <input type="text" id="image" name="image" class="board_form_input" v-model="form.image" />

                <input type="submit" value="Save" class="board_form_button_save">
                <a href="#" @click.prevent="cancel" class="board_form_button_close">Close</a>
            </form>
        </div>
    </div>
</template>

<script>

    import axios from 'axios';
    import bus from '../../bus';

    export default {
        name: 'boardEdit',
        data () {
            return {
                form: {
                    name: this.board.name,
                    image: this.board.image
                },
                showBoardForm: false,
                errorMessage: ''
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
            board: {
                required: true,
                type: Object
            },
        },
        components: {
        },
        methods: {
            async patch () {

                await axios.patch(`${this.endpoint}/${this.board.id}?access-token=${this.accesstoken}`, this.form).then(response => {

                    bus.$emit('board:edited', response.data);

                    this.showBoardForm = false;
                    this.errorMessage = '';

                }).catch(error => {
                    this.errorMessage = error.message;
                });

            },
            boardEditing() {
                this.showBoardForm = true;
                bus.$emit('board:editing')
            },
            cancel() {
                this.showBoardForm = false;
                bus.$emit('board:edit-cancelled')
            }
        },
        created() {},
        mounted() {}
    }
</script>

<style scoped lang="scss">

    .board_buttons {
        font-weight: bold;
        font-size: larger;
        padding: 3px 7px;
        margin: 3px 5px;
        color: #fff;
        cursor: pointer;
        box-shadow: inset 0 0 400px 110px rgba(0, 0, 0, .4);
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
</style>
