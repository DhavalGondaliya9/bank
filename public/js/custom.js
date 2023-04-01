let bankMatchRecord = [];
let orderPaymentMatchRecord = [];

function bank() {
    return {
        bankRecord: [],
        bankRecordKey: [],

        hideBankData() {
            let $this = this;
            this.bankRecord = bankMatchRecord;
            this.bankRecordKey = [];
            this.bankRecord.forEach(function (record, index) {
                $this.bankRecordKey.push(record);
                const element = document.getElementsByClassName("bank-record-hide"+record);
                element[0].classList.add("hidden");
            });

            fetch('/ignore-record', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ bankRecordKey:this.bankRecordKey, type:'bank' })
            })
            .then(response => response.json())
            .then(data => {
                const element = document.getElementsByClassName("matched-button");
                element[0].classList.add("hidden");
                const bankUnmatchedRecordsCount = document.querySelector('.bank-unmatched-records-count');
                const totalBankUnmatchedRecordsCount = parseInt(bankUnmatchedRecordsCount.innerHTML) - this.bankRecordKey.length;
                bankUnmatchedRecordsCount.innerHTML = totalBankUnmatchedRecordsCount;
                bankMatchRecord = [];
            });

            this.bankRecord = [];
        },

        reset() {
            this.bankRecord = bankMatchRecord;
        }

    }
}


function orderPayment() {
    return {
        orderPaymentRecord: [],
        orderPaymentRecordKey: [],

        hideOrderPaymentData() {
            let $this = this;
            this.orderPaymentRecordKey = [];
            this.orderPaymentRecord = orderPaymentMatchRecord;
            this.orderPaymentRecord.forEach(function (record, index) {
                $this.orderPaymentRecordKey.push(record);
                const element = document.getElementsByClassName("order-payment-record-hide" + record);
                element[0].classList.add("hidden");
            });

            fetch('/ignore-record', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ orderPaymentRecordKey: this.orderPaymentRecordKey, type: 'order-payment' })
            })
                .then(response => response.json())
                .then(data => {
                    const element = document.getElementsByClassName("matched-button");
                    element[0].classList.add("hidden");

                    const orderPaymentUnmatchedRecordsCount = document.querySelector('.order-payment-unmatched-records-count');
                    const totalOrderPaymentUnmatchedRecordsCount = parseInt(orderPaymentUnmatchedRecordsCount.innerHTML) - this.orderPaymentRecordKey.length;
                    orderPaymentUnmatchedRecordsCount.innerHTML = totalOrderPaymentUnmatchedRecordsCount;
                    orderPaymentMatchRecord = [];
                });

            this.orderPaymentRecord = [];
        },

        reset() {
            this.orderPaymentRecord = orderPaymentMatchRecord;
        },
    }
}

const matchedRecord = async () => {
    const [bankId] = bankMatchRecord;
    const [orderPaymentId] = orderPaymentMatchRecord;

    const bankElement = document.querySelector(`.bank-record-hide${bankId}`);
    const orderPaymentElement = document.querySelector(`.order-payment-record-hide${orderPaymentId}`);

    bankElement.classList.add('hidden');
    orderPaymentElement.classList.add('hidden');

    try {
        const response = await fetch('/match-record', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ orderPaymentRecordKey: orderPaymentMatchRecord, bankRecordKey: bankMatchRecord, })
        });

        const { success } = await response.json();

        if (success) {
            const element = document.querySelector('.matched-button');
            element.classList.add('hidden');

            document.querySelector('.bank-ignore').style.display = "none";
            document.querySelector('.order-payment-ignore').style.display = "none";

            bankMatchRecord = [];
            orderPaymentMatchRecord = [];

            bank().bankRecord = [];
            orderPayment().orderPaymentRecord = [];

            const bankMatchedRecordsCount = document.querySelector('.bank-matched-records-count');
            const totalBankMatchedRecordsCount = parseInt(bankMatchedRecordsCount.innerHTML) + 1;
            bankMatchedRecordsCount.innerHTML = totalBankMatchedRecordsCount;

            const bankUnmatchedRecordsCount = document.querySelector('.bank-unmatched-records-count');
            const totalBankUnmatchedRecordsCount = parseInt(bankUnmatchedRecordsCount.innerHTML) - 1;
            bankUnmatchedRecordsCount.innerHTML = totalBankUnmatchedRecordsCount;

            const orderPaymentmatchedRecordsCount = document.querySelector('.order-payment-matched-records-count');
            const totalOrderPaymentmatchedRecordsCount = parseInt(orderPaymentmatchedRecordsCount.innerHTML) + 1;
            orderPaymentmatchedRecordsCount.innerHTML = totalOrderPaymentmatchedRecordsCount;

            const orderPaymentUnmatchedRecordsCount = document.querySelector('.order-payment-unmatched-records-count');
            const totalOrderPaymentUnmatchedRecordsCount = parseInt(orderPaymentUnmatchedRecordsCount.innerHTML) - 1;
            orderPaymentUnmatchedRecordsCount.innerHTML = totalOrderPaymentUnmatchedRecordsCount;
        }
    } catch (error) {
        console.error(error);
    }
};


const checkMatchedRecord = (event, type) => {
    const { checked, value } = event.target;

    if (checked) {
        if (type === 'bank') {
            bankMatchRecord.push(value);


        } else if (type === 'orderPayment') {
            orderPaymentMatchRecord.push(value);
        }
    } else {
        if (type === 'bank') {
            const index = bankMatchRecord.indexOf(value);
            if (index !== -1) {
                bankMatchRecord.splice(index, 1);
            }
        } else if (type === 'orderPayment') {
            const index = orderPaymentMatchRecord.indexOf(value);
            if (index !== -1) {
                orderPaymentMatchRecord.splice(index, 1);
            }
        }
    }

    const element = document.querySelector('.matched-button');
    element.classList.toggle('hidden', !(bankMatchRecord.length === 1 && orderPaymentMatchRecord.length === 1));

    if (bankMatchRecord.length > 0) {
        document.querySelector('.bank-ignore').style.display = "block";
    }

    if (orderPaymentMatchRecord.length > 0) {
        document.querySelector('.order-payment-ignore').style.display = "block";
    }
};


