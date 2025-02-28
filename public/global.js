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
}
