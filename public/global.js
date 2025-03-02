class Mola {
    static inputNominalRupiah(selector) {
        new AutoNumeric(selector, {
            digitGroupSeparator: ".",
            decimalCharacter: ",",
            currencySymbol: "Rp ",
            currencySymbolPlacement: "p",
            unformatOnSubmit: true,
            minimumValue: "0",
        });
    }

    static inputNumber(selector) {
        new AutoNumeric(selector, {
            digitGroupSeparator: ".",
            decimalCharacter: ",",
            decimalPlaces: 0,
            unformatOnSubmit: true,
            minimumValue: "0",
        });
    }

    static unblockElement(element) {
        $(element).unblock();
    }

    static blockElement(element, message) {
        $(element).block({
            message:
                '<span class="text-semibold">' +
                (message ? message : "Please Wait") +
                "</span>",
            baseZ: 10000,
            overlayCSS: {
                backgroundColor: "rgba(0, 0, 0, 0.1)",
                opacity: 1,
                cursor: "wait",
                "backdrop-filter": "blur(0.5px)",
                "border-radius": ".4375rem",
            },
            css: {
                "z-index": 10020,
                padding: "10px 5px",
                margin: "0px",
                width: "20%",
                top: "40%",
                left: "40%",
                "text-align": "center",
                color: "rgba(82, 95, 127, 1)",
                border: "0px",
                "background-color": "rgb(255, 255, 255)",
                cursor: "wait",
                "border-radius": ".4375rem",
                // 'border': '2px rgba(82, 95, 127, 1) solid',
                "font-size": "16px",
                "min-width": "95px",
            },
        });
    }
}
