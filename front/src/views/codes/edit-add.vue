<template>
    <div class="give-me-space py-12">
        <h1 class="display-1">Dodaj kod</h1>
        <div class="row">
            <div class="col-md-8">
                <p>Wybierz do czego ma być przypisany kod</p>
                <v-btn class="mr-3" tile :outlined="to != 'place'" color="primary" @click="to = 'place'">Miejsce</v-btn><v-btn tile :outlined="to != 'sale'" color="primary" @click="to = 'sale'">Sprzedaż</v-btn>
                <v-text-field class="mt-5" label="Przypisz wartość procentową" type="number" @input="code.value = null" v-model="code.percentage" min="0" max="100"></v-text-field>
                <v-text-field label="Przypisz wartość liczbową" type="number" @input="code.percentage = null" v-model="code.value" min="0" max="100"></v-text-field>
                <p class="mt-5 body-2 grey--text">Poniżej wpisz liczbę, ile razy pojedyńczy kod może zostać użyty.</p>
                <v-text-field label="Maksymalna ilość użycia kodu" v-model="code.max_uses"></v-text-field>
            </div>
            <div class="col-md-4">
                <v-card>
                    <v-card-title>Generuj kody</v-card-title>
                </v-card>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                to: 'place',
                code: {},

            }
        }

    }
</script>