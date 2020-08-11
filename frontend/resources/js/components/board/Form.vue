<template>
    <div class="card bg-light">
        <div class="card-header" v-text="$t(isNewRecord ? 'form.board.add' : 'form.board.update')"></div>
        <div class="card-body">
            <form method="post" @submit.prevent="submit">
                <div class="form-group">
                    <label for="board-name">{{ $t('form.board.name') }}</label>
                    <input id="board-name"
                           type="text"
                           autocomplete="off"
                           class="form-control"
                           :class="validationCssClass($v.name)"
                           v-model.trim="$v.name.$model"
                           @keydown="responseErrors = responseErrors.filter((item) => item.field !== 'name')"
                    >
                    <template v-for="(validator, validatorName) in $v.name.$params">
                        <div class="invalid-feedback"
                             v-if="!$v.name[validatorName]"
                             v-t="validator && validator.message ? validator.message : validator"
                        ></div>
                    </template>
                </div>
                <div class="form-group">
                    <label for="board-image">{{ $t('form.board.image') }}</label>
                    <textarea id="board-image"
                              rows="2"
                              type="text"
                              class="form-control"
                              :class="validationCssClass($v.image)"
                              v-model.trim="$v.image.$model"
                              @keydown="responseErrors = responseErrors.filter((item) => item.field !== 'image')"
                    ></textarea>
                    <template v-for="(validator, validatorName) in $v.image.$params">
                        <div class="invalid-feedback"
                             v-if="!$v.image[validatorName]"
                             v-t="validator && validator.message ? validator.message : validator"
                        ></div>
                    </template>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success" :disabled="isSaving">
                        <spinner :state="isSaving"><i :class="['fas', isNewRecord ? 'fa-plus-square' : 'fa-save']"></i>
                        </spinner>
                        {{ isNewRecord ? $t('form.add') : $t('form.save') }}
                    </button>
                    <button type="reset" class="btn btn-secondary" @click.prevent="close" :disabled="isSaving">
                      <i class="fas fa-times"></i>
                      {{ $t('form.close') }}
                    </button>
                  <button type="submit" class="btn btn-danger" @click.prevent="remove" :disabled="isRemoving" v-if="!isNewRecord">
                    <spinner :state="isRemoving">
                      <i class="fas fa-trash-alt"></i>
                    </spinner>
                    {{ $t('form.remove') }}
                  </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import BoardService from "../../services/BoardService";
    import Bus from "../../bus";
    import Spinner from "../misc/Spinner";
    import {required, minLength, maxLength, helpers} from 'vuelidate/lib/validators';
    import {serverError} from "../../validators/validators";

    export default {
        name: "BoardForm",
        components: {Spinner},
        data() {
            return {
                // form data
                id: null,
                name: '',
                image: '',
                // states
                isSaving: false,
                isRemoving: false,
                // server errors
                responseErrors: [],
            };
        },
        validations: {
            name: {
                required: helpers.withParams({message: 'error.required'}, required),
                minLength: helpers.withParams({message: {path: 'error.tooShort', args: {min: 2}}}, minLength(2)),
                maxLength: helpers.withParams({message: {path: 'error.tooLong', args: {max: 255}}}, maxLength(255)),
                serverError: serverError('name'),
            },
            image: {
                maxLength: helpers.withParams({message: {path: 'error.tooLong', args: {max: 255}}}, maxLength(255)),
                serverError: serverError('description'),
            },
        },
        computed: {
            isNewRecord() {
                return this.id === null;
            },
        },
        methods: {
            submit(e) {

                this.$v.$touch();

                if (this.$v.$invalid) {
                    return;
                }

                this.isSaving = true;

                this.$http.request({
                    url: this.isNewRecord ? '/boards' : '/boards/' + this.id,
                    method: this.isNewRecord ? 'post' : 'put',
                    data: {
                        name: this.name,
                        image: this.image,
                    },
                }).then(resp => {
                    BoardService.fetchBoards();
                    Bus.$emit('closeModal');
                    this.reset();
                }).catch(err => {

                    if (err.response?.status === 422) {
                        this.responseErrors = err.response.data;
                        // this.$v.$touch();
                    }

                }).finally(() => {
                    this.isSaving = false;
                });

            },
            reset() {
                this.id = null;
                this.name = '';
                this.image = '';
            },
            close() {
                this.reset();
                Bus.$emit('closeModal')
            },
            remove() {

                this.isRemoving = true;

                this.$http.request({
                    url: '/boards/' + this.id,
                    method: 'delete',
                }).then(resp => {
                    BoardService.fetchBoards();
                    Bus.$emit('closeModal');
                    this.reset();
                }).catch(err => {

                    if (err.response?.status === 422) {
                        this.responseErrors = err.response.data;
                    }

                }).finally(() => {
                    this.isRemoving = false;
                });

            },
            validationCssClass(validation) {
                return {
                    'is-valid': !validation.$error && validation.$dirty,
                    'is-invalid': validation.$error,
                }
            },
        },
        created() {

            // Reset validation
            this.$v.$reset();

            BoardService.$on('edit', (item) => {
                this.id = item.id;
                this.name = item.name;
                this.image = item.image;
            });
        },
    }
</script>
