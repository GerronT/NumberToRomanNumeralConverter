<template>
    <form @submit.prevent="convert">
        <div class="error_message" v-if="validation_errors.length > 0">
            <div>Whoops! Something went wrong</div>
            <div v-for="(error, i) in validation_errors" :key="i">
                <li v-for="(issue, j) in error.values" :key="j">
                    <span class="error_list">{{ issue }}</span>
                </li>
            </div>
        </div>
        <label>Please enter an integer between 1-10000:</label>
        <input v-model="number" type="text" placeholder="Enter an integer" />
        <button>Convert</button>
    </form>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            number: null,
            validation_errors: [],
        };
    },
    methods: {
        convert() {
          this.validation_errors = [];
            axios
                .post("/api/convert-number-to-roman-numerals", { number: this.number })
                .then((res) => {
                    console.log(res.data);
                })
                .catch((error) => {
                    if (error?.response?.data?.errors) {
                        for (const [key, value] of Object.entries(error.response.data.errors)) {
                            this.validation_errors.push({ key: key, values: value });
                        }
                    }
                });
        },
    },
};
</script>

<style scoped>
.error_message {
    display: flex;
    margin-bottom: 1rem;
    flex-direction: column;
    font-size: 0.875rem;
    line-height: 1.25rem;
    color: #ef4444;
}

.error_list {
    list-style-position: inside;
}
</style>