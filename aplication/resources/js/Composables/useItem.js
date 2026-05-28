import { ref } from 'vue';
import axios from 'axios';

export function useItem() {
    const dados = ref(null);
    const carregando = ref(false);

    async function store(nome) {
        carregando.value = true;
        try {
            const res = await axios.post(route('item.store'), { nome: nome });
            return res.data;
        } catch (error) {
            console.error("Axios erro ao salvar:", error);
            return { success: false, message: error.response?.data?.message || "Erro desconhecido ao salvar" };
        } finally { 
            carregando.value = false; 
        }
    }

    async function carregarDados(termo = "") {
        carregando.value = true;
        try {
            const res = await axios.get(route("item.index"), {
                params: termo ? { search: termo } : {},
                headers: { Accept: "application/json" },
            });
            dados.value = res.data;
            return res.data;
        } catch (error) {
            console.error("Axios erro ao carregar dados:", error);
            return { success: false, message: error.response?.data?.message || "Erro ao carregar dados" };
        } finally {
            carregando.value = false;
        }
    }

    async function finding(search = "") {
        carregando.value = true;
        try {
            const res = await axios.get(route("item.index"), {
                params: { search: search },
                headers: {
                    Accept: "application/json",
                },
            });

            dados.value = res.data;
            return res.data;
        } catch (error) {
            console.error("Axios erro ao buscar:", error);
            return { success: false, message: error.response?.data?.message || "Erro desconhecido ao buscar" };
        } finally { 
            carregando.value = false; 
        }
    }

    return {
        dados,
        carregando,
        store,
        finding,
        carregarDados
    };
}