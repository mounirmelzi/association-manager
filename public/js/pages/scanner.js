function docReady(fn) {
  if (
    document.readyState === "complete" ||
    document.readyState === "interactive"
  ) {
    setTimeout(fn, 1);
  } else {
    document.addEventListener("DOMContentLoaded", fn);
  }
}

docReady(function () {
  var lastResult;
  var countResults = 0;
  function onScanSuccess(decodedText) {
    if (decodedText !== lastResult) {
      ++countResults;
      lastResult = decodedText;
      process(JSON.parse(decodedText));
    }
  }

  var html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", {
    fps: 10,
    qrbox: 250,
    rememberLastUsedCamera: true,
    showTorchButtonIfSupported: true,
  });
  html5QrcodeScanner.render(onScanSuccess);
});

function process(data) {
  const form = document.createElement("form");
  form.method = "POST";
  form.action = `${window.location.href}/qr`;

  for (const key in data) {
    if (data.hasOwnProperty(key)) {
      const input = document.createElement("input");
      input.type = "hidden";
      input.name = key;
      input.value = data[key];
      form.appendChild(input);
    }
  }

  document.body.appendChild(form);
  form.submit();
  document.body.removeChild(form);
}
