<template>
    <div class="blocoInput" :style="'width: ' + largura + '% !important'">
        <label for="">{{ labelText }}:<a class="link" v-if="formSenha">Esqueceu a senha?</a></label>
        <input
            :type="inputType"
            :name="inputType"
            :id="inputName"
            :placeholder="placeCampo"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
        />
    </div>
</template>

<script>
    export default {
        name: 'blocoInput',
        computed: {
            formSenha() {
                return this.labelText === 'Senha'
            }
        },
        props: {
            labelText: String,
            inputType: String,
            inputName: String,
            placeCampo: String,
            largura: String,
            modelValue: [String, Number]
        }
    }
</script>

<style lang="scss">
    .blocoInput {
        label {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }

        input {
            box-sizing: border-box;
            margin: .5rem 0 1rem 0;
            width: 100%;
            padding: .75rem;
            border-radius: .5rem;
            border: .1rem solid $cinza_2;
        }
    }
</style>