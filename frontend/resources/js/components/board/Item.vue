<template>
    <a href="#"
       @click.prevent=""
       class="board_name"
       v-bind:class="{ active: index === board.id }"
       :id="`board-id-${board.id}`"
       @click="changeItem"
    >
        {{ board.name }}
    </a>
</template>

<script>

    export default {
        name: 'boardItem',
        data() {
            return {

            }
        },
        computed: {},
        props: {
            board: {
                required: true,
                type: Object
            },
            index: {
                required: true,
                type: Number
            },
        },
        components: {},
        methods: {
            changeItem() {
                this.$emit('changeItem', this.board.id)
            }
        },
        created() {},
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style scoped lang="scss">
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
</style>
