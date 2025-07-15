const formatCurrency = (value, options = {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
}) => {
    if (value === null || value === undefined) {
        value = 0;
    }

    if (typeof value === "string") {
        value = parseFloat(value.replace(/[^0-9.-]+/g, ""));
    }

    return value.toLocaleString("id-ID", options);
}

export default { formatCurrency };