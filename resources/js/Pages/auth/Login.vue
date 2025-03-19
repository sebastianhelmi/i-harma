<template>
    <div class="flex flex-center bg-grey-2">
        <q-card class="login-card">
            <q-card-section class="text-center q-pt-lg">
                <h4 class="text-h4 q-mt-none q-mb-md">Login</h4>
            </q-card-section>

            <q-card-section>
                <q-form @submit.prevent="submit" class="q-gutter-md">
                    <q-input v-model="form.email" type="email" label="Email" outlined />

                    <q-input v-model="form.password" :type="isPwd ? 'password' : 'text'" label="Password" outlined>
                        <template v-slot:append>
                            <q-icon :name="isPwd ? 'visibility_off' : 'visibility'" class="cursor-pointer"
                                @click="isPwd = !isPwd" />
                        </template>
                    </q-input>

                    <div class="flex items-center justify-between">
                        <q-checkbox v-model="form.remember" label="Remember me" />
                        <q-btn flat color="primary" label="Forgot Password?" />
                    </div>

                    <q-btn type="submit" color="primary" class="full-width" label="Login" />
                </q-form>
            </q-card-section>
        </q-card>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const isPwd = ref(true)
const form = useForm({
    email: '',
    password: '',
    remember: false
})

const submit = () => {
    form.post(route('login'))
}
</script>

<style scoped>
.login-card {
    width: 100%;
    max-width: 400px;
    padding: 20px;
}
</style>
