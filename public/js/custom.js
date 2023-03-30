function bank() {
    return {
        bankRecord: [],
        bankRecordKey: [],

        hideBankData() {
            let $this = this;

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
                console.log(data);
            });

            this.bankRecord = [];
        }
    }
}

function orderPayment() {
    return {
        orderPaymentRecord: [],
        orderPaymentRecordKey: [],
        hideOrderPaymentData() {
            let $this = this;

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
                console.log(data);
            });

            this.orderPaymentRecord = [];
        }
    }
}


