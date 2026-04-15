function copyText() {
    const text = document.getElementById("codeBlock").innerText;

    navigator.clipboard.writeText(text);

    alert("Copied!");
}