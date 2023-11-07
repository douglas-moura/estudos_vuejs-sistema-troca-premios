<template>
    <div class="inputBox">
        <label for="">{{ labelText }}<a class="link" v-if="inputSenha">Esqueceu a senha?</a></label>
        <input
            :type="inputType"
            :name="inputName"
            :id="inputName"
            :placeholder="placeCampo"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            v-maska
            :data-maska="mascara"
        />
        <mdicon v-if="inputSenha && this.inputType == 'password'" name="EyeOffOutline" size="20" @click="$emit('trocarInput')" />
        <mdicon v-if="inputSenha && this.inputType == 'text'" name="EyeOutline" size="20" @click="$emit('trocarInput')" />
    </div>
</template>

<script>
    import { vMaska } from 'maska'
    
    export default {
        name: 'blocoInput',
        computed: {
            inputSenha() {
                return this.inputName === 'password'
            }
        },
        props: {
            labelText: String,
            inputType: String,
            inputName: String,
            placeCampo: String,
            modelValue: [String, Number],
            mascara: String,
        },
        data() {
            return {
                senhaTipo: true,
                options: {
                    mask: "#-#",
                    eager: true
                }
            }
        },
        directives: {
            maska: vMaska 
        },
        emits: [
            'trocarInput'
        ],
    }
</script>

<style lang="scss" scoped>
    .inputBox {
        width: 100%;
        
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
            border: .1rem solid $cinza_3;
        }

        span {
            position: absolute;
            margin-left: -2rem;
            margin-top: 1rem;
            cursor: pointer;
            color: $cinza_4;
        }
    }
</style>