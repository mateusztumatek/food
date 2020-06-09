<template>
    <div>
        <v-form
                ref="form"
                lazy-validation>
            <v-row>
                <v-col cols="12">
                    <div class="w-100">
                        <img style="max-width: 200px" v-if="product.image" :src="getSrc(product.image)">
                    </div>
                    <v-btn @click="$mediaPicker().then(res => {updateProduct(res, 'image')})"><span v-if="product.image">Edytuj zdjęcie</span><span v-else>Dodaj zdjęcie</span></v-btn>
                    <input type="file" id="file_input" @change="uploadLogo()" style="display: none" ref="file_input"></input>
                    <v-text-field type="hidden" class="notInput" :value="product.image"></v-text-field>

                </v-col>
                <v-col cols="12">
                    <v-select
                            :value="product.place"
                            :items="places"
                            item-text="name"
                            :item-value="(item) => {return item}"
                            @input="updateProduct($event.id, 'place_id')"
                            :rules="placeRules"
                            label="Wybierz miejsce do którego chcesz przyporządkować produkt"
                            hide-details
                            prepend-icon="mdi-shopping"
                            single-line
                    ></v-select>
                </v-col>
                <v-col cols="12">
                    <v-text-field
                            :rules="nameRules"
                            :value="product.name"
                            label="Nazwa produktu"
                            @input="updateProduct($event, 'name')"
                    ></v-text-field>
                </v-col>
                <v-col cols="12">
                    <v-textarea
                            :value="product.description"
                            :label="$t('Opis produktu')"
                            @input="updateProduct($event, 'description')"
                    ></v-textarea>
                </v-col>
                <v-col cols="12">
                    <v-combobox
                            :value="product.tags"
                            item-text="tag"
                            chips
                            clearable
                            label="Tagi"
                            @input="updateProduct($event, 'tags')"
                            multiple
                    >
                        <template v-slot:selection="{ attrs, item, select, selected }">
                            <v-chip
                                    v-bind="attrs"
                                    :input-value="selected"
                                    close
                                    color="blue"
                                    @click="product.tags.splice(product.tags.findIndex(e => e == item), 1)"
                                    @click:close="product.tags.splice(product.tags.findIndex(e => e == item), 1)"
                            >
                                <strong>{{ (item.tag)? item.tag : item }}</strong>
                            </v-chip>
                        </template>
                    </v-combobox>
                </v-col>
                <v-col cols="12">
                    <v-btn color="primary" :disabled="!valid" @click="$emit('next_step', 2)">Przejdź dalej</v-btn>
                </v-col>
            </v-row>
        </v-form>

    </div>
</template>
<script>
    import {upload} from "../../../api/upload";

    export default {
        name: 'First_component',
        data(){
            return{
                valid: false,
                tags: [],
                placeRules:[
                    v => !!v || 'Miejsce jest wymagane',
                ],
                nameRules:[
                    v => !!v || 'Nazwa jest wymagana',
                ],
                imageRules:[
                    v => !!v || 'Zdjęcie jest wymagane',
                ]
            }
        },
        computed:{
          product(){
              return this.$store.getters.products.new_product;
          },
          places(){
              return this.$store.getters.places;
          }
        },
        mounted(){
        },
        methods:{
            validate(){
                if(this.$refs.form.validate()){
                    this.valid = true;
                    this.$emit('done', 1, true);
                } else{
                    this.valid = false;
                    this.$emit('done', 1, false);
                }
            },
            updateProduct(e, prop){
                this.$store.commit('products/SET_NEW_PRODUCT_PROPERTY', {value: e, prop: prop});
                this.validate();
            },
            uploadLogo(){
                var formData = new FormData();
                formData.append('files[]', this.$refs.file_input.files[0]);
                if(this.$refs.file_input.files[0]){
                    this.startLoading();
                    upload(formData, 'products').then(response => {
                        this.stopLoading();
                        this.$store.commit('products/SET_NEW_PRODUCT_PROPERTY', {value: response.data[0], prop: 'image'});
                        setTimeout(() => {
                            this.validate();
                        }, 100)
                    }).catch(e => {
                        this.stopLoading();
                    })
                }

            }
        }
    }
</script>