<template>
  <form @submit.prevent="onSubmit" class="space-y-4" novalidate>
    <Textinput
      label="Email"
      type="email"
      placeholder="Type your email"
      name="email"
      v-model="email"
      :error="emailError"
      classInput="h-[48px]"
    />
    <Textinput
      label="Senha"
      type="password"
      placeholder="8+ caracteres, 1 letra maiúscula"
      name="password"
      v-model="password"
      :error="passwordError"
      hasicon
      classInput="h-[48px]"
    />

    <div class="flex justify-between">
      <label class="cursor-pointer flex items-start">
        <input
          type="checkbox"
          class="hidden"
          @change="() => (checkbox = !checkbox)"
        />
        <span
          class="h-4 w-4 border rounded flex-none inline-flex mr-3 relative top-1 transition-all duration-150"
          :class="checkbox
            ? 'ring-2 ring-black-500 dark:ring-offset-slate-600 dark:ring-slate-900 dark:bg-slate-900 ring-offset-2 bg-slate-900'
            : 'bg-slate-100 dark:bg-slate-600 border-slate-100 dark:border-slate-600'"
        >
          <img
            src="@/assets/images/icon/ck-white.svg"
            alt=""
            class="h-[10px] w-[10px] block m-auto"
            v-if="checkbox"
          />
        </span>
        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">
          Mantenha-me conectado
        </span>
      </label>
      <router-link
        to="/forgot-password"
        class="text-sm text-slate-800 dark:text-slate-400 leading-6 font-medium"
        >Esqueceu sua senha?</router-link
      >
    </div>

    <button type="submit" class="btn btn-dark block w-full text-center">
      Entrar
    </button>

    <p v-if="auth.error" class="text-red-600 mt-2 text-center">{{ auth.error }}</p>
  </form>
</template>

<script setup>
import Textinput from "@/components/Textinput";
import { useField, useForm } from "vee-validate";
import * as yup from "yup";

import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";
import { useAuthStore } from "@/store/authStore";
import { ref } from "vue";

const checkbox = ref(false);

const schema = yup.object({
  email: yup.string().required("Email is required").email(),
  password: yup.string().required("Password is required").min(8),
});

const toast = useToast();
const router = useRouter();

const formValues = {
  email: 'alex@gmail.com',
  password: '12345678',
};

const { handleSubmit } = useForm({
  validationSchema: schema,
  initialValues: formValues,
});

const { value: email, errorMessage: emailError } = useField("email");
const { value: password, errorMessage: passwordError } = useField("password");

const auth = useAuthStore();

const onSubmit = handleSubmit(async (values) => {
  const success = await auth.login(values.email, values.password);
  if (success) {
    router.push("/app/home");
    toast.success(`Usuário ${auth.user?.person?.name ?? 'Usuário'} logado com sucesso`, { timeout: 2000 });
  } else {
    toast.error(auth.error || "Login failed", { timeout: 2000 });
  }
});

</script>

