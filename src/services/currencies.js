export const optionLabel = (opt) => {
  if (opt?.code) {
    const code = opt.code.toUpperCase();
    return `${opt.name} (${code})`;
  }
  return opt.name;
};

export const currencies = [
  {
    name: "US Dollar",
    code: "usd",
  },
  {
    name: "Canadian Dollar",
    code: "cad",
  },
  {
    name: "Euro",
    code: "eur",
  },
  {
    name: "United Arab Emirates Dirham",
    code: "aed",
  },
  {
    name: "Afghan Afghani",
    code: "afn",
  },
  {
    name: "Albanian Lek",
    code: "all",
  },
  {
    name: "Armenian Dram",
    code: "amd",
  },
  {
    name: "Argentine Peso",
    code: "ars",
  },
  {
    name: "Australian Dollar",
    code: "aud",
  },
  {
    name: "Azerbaijani Manat",
    code: "azn",
  },
  {
    name: "Bosnia-Herzegovina Convertible Mark",
    code: "bam",
  },
  {
    name: "Bangladeshi Taka",
    code: "bdt",
  },
  {
    name: "Bulgarian Lev",
    code: "bgn",
  },
  {
    name: "Bahraini Dinar",
    code: "bhd",
  },
  {
    name: "Burundian Franc",
    code: "bif",
  },
  {
    name: "Brunei Dollar",
    code: "bnd",
  },
  {
    name: "Bolivian Boliviano",
    code: "bob",
  },
  {
    name: "Brazilian Real",
    code: "brl",
  },
  {
    name: "Botswanan Pula",
    code: "bwp",
  },
  {
    name: "Belarusian Ruble",
    code: "byn",
  },
  {
    name: "Belize Dollar",
    code: "bzd",
  },
  {
    name: "Congolese Franc",
    code: "cdf",
  },
  {
    name: "Swiss Franc",
    code: "chf",
  },
  {
    name: "Chilean Peso",
    code: "clp",
  },
  {
    name: "Chinese Yuan",
    code: "cny",
  },
  {
    name: "Colombian Peso",
    code: "cop",
  },
  {
    name: "Costa Rican Colón",
    code: "crc",
  },
  {
    name: "Cape Verdean Escudo",
    code: "cve",
  },
  {
    name: "Czech Republic Koruna",
    code: "czk",
  },
  {
    name: "Djiboutian Franc",
    code: "djf",
  },
  {
    name: "Danish Krone",
    code: "dkk",
  },
  {
    name: "Dominican Peso",
    code: "dop",
  },
  {
    name: "Algerian Dinar",
    code: "dzd",
  },
  {
    name: "Estonian Kroon",
    code: "eek",
  },
  {
    name: "Egyptian Pound",
    code: "egp",
  },
  {
    name: "Eritrean Nakfa",
    code: "ern",
  },
  {
    name: "Ethiopian Birr",
    code: "etb",
  },
  {
    name: "British Pound Sterling",
    code: "gbp",
  },
  {
    name: "Georgian Lari",
    code: "gel",
  },
  {
    name: "Ghanaian Cedi",
    code: "ghs",
  },
  {
    name: "Guinean Franc",
    code: "gnf",
  },
  {
    name: "Guatemalan Quetzal",
    code: "gtq",
  },
  {
    name: "Hong Kong Dollar",
    code: "hkd",
  },
  {
    name: "Honduran Lempira",
    code: "hnl",
  },
  {
    name: "Croatian Kuna",
    code: "hrk",
  },
  {
    name: "Hungarian Forint",
    code: "huf",
  },
  {
    name: "Indonesian Rupiah",
    code: "idr",
  },
  {
    name: "Israeli New Sheqel",
    code: "ils",
  },
  {
    name: "Indian Rupee",
    code: "inr",
  },
  {
    name: "Iraqi Dinar",
    code: "iqd",
  },
  {
    name: "Iranian Rial",
    code: "irr",
  },
  {
    name: "Icelandic Króna",
    code: "isk",
  },
  {
    name: "Jamaican Dollar",
    code: "jmd",
  },
  {
    name: "Jordanian Dinar",
    code: "jod",
  },
  {
    name: "Japanese Yen",
    code: "jpy",
  },
  {
    name: "Kenyan Shilling",
    code: "kes",
  },
  {
    name: "Cambodian Riel",
    code: "khr",
  },
  {
    name: "Comorian Franc",
    code: "kmf",
  },
  {
    name: "South Korean Won",
    code: "krw",
  },
  {
    name: "Kuwaiti Dinar",
    code: "kwd",
  },
  {
    name: "Kazakhstani Tenge",
    code: "kzt",
  },
  {
    name: "Lebanese Pound",
    code: "lbp",
  },
  {
    name: "Sri Lankan Rupee",
    code: "lkr",
  },
  {
    name: "Lithuanian Litas",
    code: "ltl",
  },
  {
    name: "Latvian Lats",
    code: "lvl",
  },
  {
    name: "Libyan Dinar",
    code: "lyd",
  },
  {
    name: "Moroccan Dirham",
    code: "mad",
  },
  {
    name: "Moldovan Leu",
    code: "mdl",
  },
  {
    name: "Malagasy Ariary",
    code: "mga",
  },
  {
    name: "Macedonian Denar",
    code: "mkd",
  },
  {
    name: "Myanma Kyat",
    code: "mmk",
  },
  {
    name: "Macanese Pataca",
    code: "mop",
  },
  {
    name: "Mauritian Rupee",
    code: "mur",
  },
  {
    name: "Mexican Peso",
    code: "mxn",
  },
  {
    name: "Malaysian Ringgit",
    code: "myr",
  },
  {
    name: "Mozambican Metical",
    code: "mzn",
  },
  {
    name: "Namibian Dollar",
    code: "nad",
  },
  {
    name: "Nigerian Naira",
    code: "ngn",
  },
  {
    name: "Nicaraguan Córdoba",
    code: "nio",
  },
  {
    name: "Norwegian Krone",
    code: "nok",
  },
  {
    name: "Nepalese Rupee",
    code: "npr",
  },
  {
    name: "New Zealand Dollar",
    code: "nzd",
  },
  {
    name: "Omani Rial",
    code: "omr",
  },
  {
    name: "Panamanian Balboa",
    code: "pab",
  },
  {
    name: "Peruvian Nuevo Sol",
    code: "pen",
  },
  {
    name: "Philippine Peso",
    code: "php",
  },
  {
    name: "Pakistani Rupee",
    code: "pkr",
  },
  {
    name: "Polish Zloty",
    code: "pln",
  },
  {
    name: "Paraguayan Guarani",
    code: "pyg",
  },
  {
    name: "Qatari Rial",
    code: "qar",
  },
  {
    name: "Romanian Leu",
    code: "ron",
  },
  {
    name: "Serbian Dinar",
    code: "rsd",
  },
  {
    name: "Russian Ruble",
    code: "rub",
  },
  {
    name: "Rwandan Franc",
    code: "rwf",
  },
  {
    name: "Saudi Riyal",
    code: "sar",
  },
  {
    name: "Sudanese Pound",
    code: "sdg",
  },
  {
    name: "Swedish Krona",
    code: "sek",
  },
  {
    name: "Singapore Dollar",
    code: "sgd",
  },
  {
    name: "Somali Shilling",
    code: "sos",
  },
  {
    name: "Syrian Pound",
    code: "syp",
  },
  {
    name: "Thai Baht",
    code: "thb",
  },
  {
    name: "Tunisian Dinar",
    code: "tnd",
  },
  {
    name: "Tongan Paʻanga",
    code: "top",
  },
  {
    name: "Turkish Lira",
    code: "try",
  },
  {
    name: "Trinidad and Tobago Dollar",
    code: "ttd",
  },
  {
    name: "New Taiwan Dollar",
    code: "twd",
  },
  {
    name: "Tanzanian Shilling",
    code: "tzs",
  },
  {
    name: "Ukrainian Hryvnia",
    code: "uah",
  },
  {
    name: "Ugandan Shilling",
    code: "ugx",
  },
  {
    name: "Uruguayan Peso",
    code: "uyu",
  },
  {
    name: "Uzbekistan Som",
    code: "uzs",
  },
  {
    name: "Venezuelan Bolívar",
    code: "vef",
  },
  {
    name: "Vietnamese Dong",
    code: "vnd",
  },
  {
    name: "CFA Franc BEAC",
    code: "xaf",
  },
  {
    name: "CFA Franc BCEAO",
    code: "xof",
  },
  {
    name: "Yemeni Rial",
    code: "yer",
  },
  {
    name: "South African Rand",
    code: "zar",
  },
  {
    name: "Zambian Kwacha",
    code: "zmk",
  },
  {
    name: "Zimbabwean Dollar",
    code: "zwl",
  },
];
