let bankMatchRecord = [];
let orderPaymentMatchRecord = [];

const bankIgnoreRecordList = [];
const orderPaymentIgnoreRecordList = [];

function bank() {
    return {
        bankRecord: [],
        bankRecordKey: [],

        hideBankData() {
            let $this = this;

            this.bankRecord = bankMatchRecord;
            this.bankRecordKey = [];

            this.bankRecord.forEach(function (record, index) {
                bankIgnoreRecordList.push(bankUnmatchRecordList[record]);
                $this.bankRecordKey.push(record);
                const element = document.getElementsByClassName("bank-record-hide" + record);
                element[0].classList.add("hidden");
            });

            fetch('/ignore-record', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ bankRecordKey: this.bankRecordKey, bankIgnoreRecordList: bankIgnoreRecordList, type: 'bank' })
            })
                .then(response => response.json())
                .then(data => {
                    const element = document.getElementsByClassName("matched-button");
                    element[0].classList.add("hidden");

                    const bankUnmatchedRecordsCount = document.querySelector('.bank-unmatched-records-count');
                    const totalBankUnmatchedRecordsCount = parseInt(bankUnmatchedRecordsCount.innerHTML) - this.bankRecordKey.length;
                    bankUnmatchedRecordsCount.innerHTML = totalBankUnmatchedRecordsCount;

                    bankMatchRecord = [];

                    const bankIgnoreRecordsCount = document.querySelector('.bank-ignore-records-count').innerHTML;
                    document.querySelector('.bank-ignore-records-count').innerHTML = parseInt(bankIgnoreRecordsCount) + this.bankRecordKey.length;

                    updateBankIgnoreList();
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
                orderPaymentIgnoreRecordList.push(orderPaymentUnmatchRecordList[record]);
                $this.orderPaymentRecordKey.push(record);

                const element = document.getElementsByClassName("order-payment-record-hide" + record);
                element[0].classList.add("hidden");
            });

            fetch('/ignore-record', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ orderPaymentRecordKey: this.orderPaymentRecordKey, orderPaymentIgnoreRecordList: orderPaymentIgnoreRecordList, type: 'order-payment' })
            })
                .then(response => response.json())
                .then(data => {
                    const element = document.getElementsByClassName("matched-button");
                    element[0].classList.add("hidden");

                    const orderPaymentUnmatchedRecordsCount = document.querySelector('.order-payment-unmatched-records-count');
                    const totalOrderPaymentUnmatchedRecordsCount = parseInt(orderPaymentUnmatchedRecordsCount.innerHTML) - this.orderPaymentRecordKey.length;

                    orderPaymentUnmatchedRecordsCount.innerHTML = totalOrderPaymentUnmatchedRecordsCount;
                    orderPaymentMatchRecord = [];

                    const orderPaymentIgnoreRecordsCount = document.querySelector('.order-payment-ignore-records-count').innerHTML;
                    document.querySelector('.order-payment-ignore-records-count').innerHTML = parseInt(orderPaymentIgnoreRecordsCount) + this.orderPaymentRecordKey.length;

                    updateOrderPaymentIgnoreList();
                });

            this.orderPaymentRecord = [];
        },

        reset() {
            this.orderPaymentRecord = orderPaymentMatchRecord;
        },
    }
}

const matchedRecord = async () => {
    const bankId = bankMatchRecord;
    const orderPaymentId = orderPaymentMatchRecord;

    const bankElement = document.querySelector(`.bank-record-hide${bankId}`);
    const orderPaymentElement = document.querySelector(`.order-payment-record-hide${orderPaymentId}`);

    bankElement.classList.add('hidden');
    orderPaymentElement.classList.add('hidden');

    try {
        const response = await fetch('/match-record', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ orderPaymentRecordKey: orderPaymentMatchRecord, bankRecordKey: bankMatchRecord, })
        });

        const { success } = await response.json();

        if (!success) {
            return;
        }

        let addbankRecord = bankUnmatchRecordList[bankMatchRecord];
        let orderPaymentRecord = orderPaymentUnmatchRecordList[orderPaymentMatchRecord];

        bankMatchRecordList.push(addbankRecord);
        orderPaymentMatchRecordList.push(orderPaymentRecord);

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

        updateMatchList();
    } catch (error) {
        console.error(error);
    }
};

const updateBankIgnoreList = () => {
    let bankIgnoreList = document.getElementById("bankIgnoreRecord").innerHTML;

    bankIgnoreRecordList.find((value, index) => {
        document.getElementsByClassName("bankIgnoreRecord")[0].innerHTML += bankIgnoreList;

        document.querySelectorAll(".bank-ignore-date")[index].innerHTML = value[0];
        document.querySelectorAll(".bank-ignore-reference")[index].innerHTML = value[6];
        document.querySelectorAll(".bank-ignore-credit")[index].innerHTML = value[8];
    });

};

const updateOrderPaymentIgnoreList = () => {
    let orderPaymentIgnoreList = document.getElementById("orderPaymentIgnoreRecord").innerHTML;

    orderPaymentIgnoreRecordList.find((value, index) => {
        document.getElementsByClassName("orderPaymentIgnoreRecord")[0].innerHTML += orderPaymentIgnoreList;

        document.querySelectorAll(".order-payment-ignore-date")[index].innerHTML = value[3];
        document.querySelectorAll(".order-payment-ignore-order-id")[index].innerHTML = value[0];
        document.querySelectorAll(".order-payment-ignore-reference")[index].innerHTML = value[1];
        document.querySelectorAll(".order-payment-ignore-credit")[index].innerHTML = value[2];
    });

};

const updateMatchList = () => {
    document.querySelector('.bankMatchRecord').innerHTML = '';
    let bankList = document.getElementById("bankMatchRecord").innerHTML;

    bankMatchRecordList.find((value, index) => {
        document.getElementsByClassName("bankMatchRecord")[0].innerHTML += bankList;

        document.querySelectorAll(".bank-matched-date")[index].innerHTML = value[0];
        document.querySelectorAll(".bank-matched-reference")[index].innerHTML = value[6];
        document.querySelectorAll(".bank-matched-credit")[index].innerHTML = value[8];
    });

    document.querySelector('.orderPaymentMatchRecord').innerHTML = '';
    let orderpaymentList = document.getElementById("orderPaymentMatchRecord").innerHTML;

    orderPaymentMatchRecordList.find((value, index) => {
        document.getElementsByClassName("orderPaymentMatchRecord")[0].innerHTML += orderpaymentList;

        document.querySelectorAll(".order-payment-matched-date")[index].innerHTML = value[3];
        document.querySelectorAll(".order-payment-matched-order-id")[index].innerHTML = value[0];
        document.querySelectorAll(".order-payment-matched-reference")[index].innerHTML = value[1];
        document.querySelectorAll(".order-payment-matched-credit")[index].innerHTML = value[2];
    });
};

const checkMatchedRecord = (event, type) => {
    const { checked, value } = event.target;
    const records = type === 'bank' ? bankMatchRecord : orderPaymentMatchRecord;

    checked ? records.push(value) : records.splice(records.indexOf(value), 1);

    const isMatched = bankMatchRecord.length === 1 && orderPaymentMatchRecord.length === 1;
    document.querySelector('.matched-button').classList.toggle('hidden', !isMatched);

    if (records.length > 0) {
        document.querySelector(`.${type}-ignore`).style.display = 'block';
    }
};

