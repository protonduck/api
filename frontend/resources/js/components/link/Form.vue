<template>
    <div class="card bg-light">
        <div class="card-header" v-text="$t(isNewRecord ? 'form.link.add' : 'form.link.update')"></div>
        <div class="card-body">
            <form method="post" @submit.prevent="submit">
                <div class="form-group">
                    <label for="link-title">{{ $t('form.link.title') }}</label>
                    <input id="link-title"
                           type="text"
                           autocomplete="off"
                           class="form-control"
                           :class="validationCssClass($v.title)"
                           v-model.trim="$v.title.$model"
                           @keydown="responseErrors = responseErrors.filter((item) => item.field !== 'title')"
                    >
                    <template v-for="(validator, validatorName) in $v.title.$params">
                        <div class="invalid-feedback"
                             v-if="!$v.title[validatorName]"
                             v-t="validator && validator.message ? validator.message : validator"
                        ></div>
                    </template>
                </div>
                <div class="form-group">
                    <label for="link-description">{{ $t('form.link.description') }}</label>
                    <textarea id="link-description"
                              rows="2"
                              type="text"
                              class="form-control"
                              :class="validationCssClass($v.description)"
                              v-model.trim="$v.description.$model"
                              @keydown="responseErrors = responseErrors.filter((item) => item.field !== 'description')"
                    ></textarea>
                    <template v-for="(validator, validatorName) in $v.description.$params">
                        <div class="invalid-feedback"
                             v-if="!$v.description[validatorName]"
                             v-t="validator && validator.message ? validator.message : validator"
                        ></div>
                    </template>
                </div>
                <div class="form-group">
                    <label for="link-url">{{ $t('form.link.url') }}</label>
                    <input id="link-url"
                           type="text"
                           autocomplete="off"
                           class="form-control"
                           :class="validationCssClass($v.url)"
                           v-model.trim="$v.url.$model"
                           @keydown="responseErrors = responseErrors.filter((item) => item.field !== 'url')"
                    >
                    <template v-for="(validator, validatorName) in $v.url.$params">
                        <div class="invalid-feedback"
                             v-if="!$v.url[validatorName]"
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
    import LinkService from "../../services/LinkService";
    import Bus from "../../bus";
    import Spinner from "../misc/Spinner";
    import {required, minLength, maxLength, helpers} from 'vuelidate/lib/validators';
    import {serverError} from "../../validators/validators";
    import BoardService from "../../services/BoardService";

    export default {
        name: "LinkForm",
        components: {Spinner},
        data() {
            return {
                // form data
                id: null,
                title: '',
                description: '',
                url: '',
                category_id: null,
                // states
                isSaving: false,
                isRemoving: false,
                // server errors
                responseErrors: [],
            };
        },
        validations: {
            title: {
                required: helpers.withParams({message: 'error.required'}, required),
                minLength: helpers.withParams({message: {path: 'error.tooShort', args: {min: 2}}}, minLength(2)),
                maxLength: helpers.withParams({message: {path: 'error.tooLong', args: {max: 255}}}, maxLength(255)),
                serverError: serverError('title'),
            },
            description: {
                maxLength: helpers.withParams({message: {path: 'error.tooLong', args: {max: 255}}}, maxLength(255)),
                serverError: serverError('description'),
            },
            url: {
                minLength: helpers.withParams({message: {path: 'error.tooShort', args: {min: 2}}}, minLength(2)),
                maxLength: helpers.withParams({message: {path: 'error.tooLong', args: {max: 255}}}, maxLength(255)),
                serverError: serverError('url'),
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
                    url: this.isNewRecord ? '/links' : '/links/' + this.id,
                    method: this.isNewRecord ? 'post' : 'put',
                    data: {
                        id: this.id,
                        category_id: this.category_id,
                        title: this.title,
                        description: this.description,
                        url: this.url,
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
                this.category_id = null;
                this.title = '';
                this.description = '';
                this.url = '';
            },
            close() {
                this.reset();
                Bus.$emit('closeModal')
            },
            remove() {

                this.isRemoving = true;

                this.$http.request({
                  url: '/links/' + this.id,
                  method: 'delete',
                }).then(resp => {
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

            LinkService.$on('edit', (item) => {
                this.id = item.id;
                this.category_id = item.category_id;
                this.title = item.title;
                this.description = item.description;
                this.url = item.url;
            });

        },
    }
</script>
