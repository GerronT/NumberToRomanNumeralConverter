<template>
    <form @submit.prevent="submitNumber" class="m-4">
        <!-- VALIDATION ERRORS -->
        <div class="flex flex-col mb-4 text-red-500 text-sm items-center justify-center" v-if="validation_errors.length > 0">
            <div>Whoops! Something went wrong</div>
            <div v-for="(error, i) in validation_errors" :key="i">
                <li v-for="(issue, j) in error.values" :key="j">
                    <span class="list-inside">{{ issue }}</span>
                </li>
            </div>
        </div>

        <!-- MAIN INPUT FORM -->
        <div class="flex flex-col justify-center items-center text-center mx-auto gap-4">
            <div class="flex flex-col space-y-2">
                <label class="font-bold">Please enter an integer between 1-100000:</label>
                <input class="text-sm w-32 mx-auto border-pink-500 px-2 py-1 bg-gray-50 block shadow-sm border-gray-50 rounded-md" v-model="number" type="text" placeholder="Enter an integer" />
            </div>
            <div class="flex flex-row space-x-1">
                <input type="checkbox" id="no_m_mode" v-model="no_m_mode" />
                <label>Replace 'M' with '<span class="overline">I</span>' for values greater than 1000 <a class="underline text-blue-500" href="https://mammothmemory.net/maths/numbers/roman-numerals/roman-numerals-from-1000-to-1-million.html" target="_blank">(Refer to this link)</a></label>
            </div>
            <button class="px-2 py-1 bg-yellow-500 hover:bg-yellow-600 rounded-md text-white">Convert to Roman Numerals</button>
            <div v-if="answer"><strong>Answer:</strong> <span v-html="answer"></span></div>
        </div>
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
                    let to_be_barred_replaced = to_be_barred.replaceAll("I", "M");

                    for (let i = 0; i < to_be_barred_replaced.length; i++) {
                        if (to_be_barred_replaced[i] !== "M") {
                            barred_replaced += '<span style="text-decoration: overline;">' + to_be_barred_replaced[i] + "</span>";
                        } else {
                            barred_replaced += to_be_barred_replaced[i];
                        }
                    }
                } else {
                    // If 'No M Mode' is on, convert Ms to Is and bar in its entirety
                    let to_be_barred_replaced = to_be_barred.replaceAll("M", "I");

                    barred_replaced = '<span style="text-decoration: overline;">' + to_be_barred_replaced + "</span>";
                }

                this.answer = barred_replaced + value.slice(th_sign_i + 1);
            } else {
                this.answer = value;
            }
        },
    },
};
</script>

<style>
</style>