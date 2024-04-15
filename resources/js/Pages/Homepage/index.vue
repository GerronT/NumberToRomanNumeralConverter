<template>
    <form @submit.prevent="submitNumber">
        <div class="error_message" v-if="validation_errors.length > 0">
            <div>Whoops! Something went wrong</div>
            <div v-for="(error, i) in validation_errors" :key="i">
                <li v-for="(issue, j) in error.values" :key="j">
                    <span class="error_list">{{ issue }}</span>
                </li>
            </div>
        </div>
        <label>Please enter an integer between 1-100000:</label>
        <input v-model="number" type="text" placeholder="Enter an integer" />
        <button>Convert</button>
        <div>
            <input type="checkbox" id="no_m_mode" v-model="no_m_mode" />
            <label><strong>Do not</strong> include 'M' for values greater than 1000 <a href="https://mammothmemory.net/maths/numbers/roman-numerals/roman-numerals-from-1000-to-1-million.html" target="_blank">(Refer to this link)</a></label>
        </div>
        <div v-if="answer"><strong>Answer:</strong> <span v-html="answer"></span></div>
    </form>
</template>
<script>
import axios from "axios";
export default {
    data() {
        return {
            number: null,
            validation_errors: [],
            answer: null,
            no_m_mode: false,
        };
    },
    methods: {
        submitNumber() {
            this.validation_errors = [];
            axios
                .post("/api/convert-number-to-roman-numerals", { number: this.number })
                .then((res) => {
                    this.processAnswer(res.data);
                })
                .catch((error) => {
                    if (error?.response?.data?.errors) {
                        for (const [key, value] of Object.entries(error.response.data.errors)) {
                            this.validation_errors.push({ key: key, values: value });
                        }
                    }
                });
        },
        // This method will apply the bars to necessary characters and will replace barred Is with Ms when necessary.
        processAnswer(value) {
            // "_" sign is the reference point of where the thousands' splits from the remainder
            let th_sign_i = value.indexOf("_");

            if (th_sign_i !== -1) {
                let to_be_barred = value.slice(0, th_sign_i);
                let barred_replaced = "";

                if (!this.no_m_mode) {
                    // If 'No M Mode' is off, convert all instances of I with M and ignore them during barring process
                    let to_be_barred_replaced = to_be_barred.replace("I", "M");

                    for (let i = 0; i < to_be_barred_replaced.length; i++) {
                        if (to_be_barred_replaced[i] !== "M") {
                            barred_replaced += '<span style="text-decoration: overline;">' + to_be_barred_replaced[i] + "</span>";
                        } else {
                            barred_replaced += to_be_barred_replaced[i];
                        }
                    }
                } else {
                    // If 'No M Mode' is on, just bar in its entirety
                    barred_replaced = '<span style="text-decoration: overline;">' + to_be_barred + "</span>";
                }

                this.answer = barred_replaced + value.slice(th_sign_i + 1);
            } else {
                this.answer = value;
            }
        },
    },
};
</script>

<style scoped>
.form_class {
  display: flex; 
margin-top: 0.5rem; 
flex-direction: column; 
}
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