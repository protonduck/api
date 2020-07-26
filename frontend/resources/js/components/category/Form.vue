<template>
    <div class="card bg-light">
        <div class="card-header" v-text="isNewRecord ? 'Add category' : 'Update category'"></div>
        <div class="card-body">
            <form method="post" @submit.prevent="submit">
                <div class="form-group">
                    <input class="form-control" type="text" v-model="name" placeholder="Name">
                </div>
                <div class="form-group">
                    <textarea rows="2" class="form-control" type="text" v-model="description"
                        placeholder="Description"></textarea>
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" v-model="color" placeholder="Color">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" v-model="icon" placeholder="Icon">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success"
                            v-text="isNewRecord ? 'Add' : 'Save'"></button>
                    <button type="reset" class="btn btn-danger"
                            @click.prevent="close">Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import CategoryService from "../../services/CategoryService";
    import BoardService from "../../services/BoardService";
    import Bus from "../../bus"

    export default {
        name: "CategoryForm",
        data() {
            return {
                id: null,
                name: '',
                description: '',
                color: '',
                icon: '',
            };
        },
        computed: {
            isNewRecord() {
                return this.id === null;
            },
        },
        methods: {
            submit(e) {
                this.$http.request({
                    url: this.isNewRecord ? '/categories' : '/categories/' + this.id,
                    method: this.isNewRecord ? 'post' : 'put',
                    data: {
                        board_id: BoardService.activeBoardId,
                        name: this.name,
                        description: this.description,
                        color: this.color,
                        icon: this.icon,
                    },
                }).then(resp => {
                    this.reset();
                    BoardService.fetchBoards();
                    Bus.$emit('closeModal');
                }).catch(err => {
                    console.log(err);
                });

            },
            reset() {
                this.id = null;
                this.name = '';
                this.description = '';
                this.color = '';
                this.icon = '';
            },
            close() {
                this.reset();
                Bus.$emit('closeModal')
            }
        },
        created() {
            CategoryService.$on('edit', (item) => {
                this.id = item.id;
                this.name = item.name;
                this.description = item.description;
                this.color = item.color;
                this.icon = item.icon;
            });

            CategoryService.$on('reset', () => {
                reset();
            });
        }
    }
</script>

<style scoped>

</style>
