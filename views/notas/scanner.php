<?php declare(strict_types=1); ?>

<h2>Ler QR Code da Nota Fiscal</h2>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
<?php endif; ?>

<p style="font-size:.9rem; color:#555; margin-bottom:1rem;">
    Aponte a câmera para o QR Code da nota fiscal (NFC-e) do supermercado.
    Os produtos serão importados automaticamente para o estoque.
</p>

<!-- Seletor de câmera -->
<div class="form-group" style="max-width:500px; margin-bottom:1rem;">
    <label for="cameraSelect">Câmera</label>
    <select id="cameraSelect">
        <option value="">Carregando câmeras...</option>
    </select>
</div>

<div id="scanner-status" class="alert" style="background:#e0f2fe; border:1px solid #7dd3fc; color:#0369a1; display:none;">
    Aguardando QR Code...
</div>

<div id="reader" style="max-width:500px; margin-bottom:1rem;"></div>

<div id="status-success" style="display:none;" class="alert alert-success">
    <strong>QR Code detectado!</strong><br>
    <span id="url-preview" style="font-size:.8rem; word-break:break-all;"></span><br>
    <span style="margin-top:.5rem; display:inline-block;">Importando produtos, aguarde...</span>
</div>

<form id="qrForm" method="POST" action="<?= BASE_URL ?>/notas/importar" style="display:none;">
    <input type="hidden" name="url" id="qrUrl">
</form>

<hr style="margin:1.5rem 0;">

<details>
    <summary style="cursor:pointer; font-size:.9rem; color:#555;">Não conseguiu ler? Digite a chave de acesso da nota</summary>
    <p style="font-size:.85rem; color:#777; margin-top:.5rem;">
        A chave de acesso são os 44 números que aparecem na nota fiscal, logo abaixo do código de barras.
    </p>
    <form method="POST" action="<?= BASE_URL ?>/notas/importar" style="max-width:500px; margin-top:.5rem;">
        <div class="form-group">
            <label for="chave_acesso">Chave de Acesso (44 dígitos)</label>
            <input
                type="text"
                id="chave_acesso"
                name="chave_acesso"
                placeholder="Ex: 41260412345678000190650010000012341000012340"
                maxlength="44"
                style="font-family:monospace; letter-spacing:1px;"
            >
        </div>
        <button type="submit" class="btn btn-primary">Importar</button>
    </form>
</details>

<div style="margin-top:1rem;">
    <a href="<?= BASE_URL ?>/produtos" class="btn btn-secondary">Voltar ao Estoque</a>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var scannerStatus = document.getElementById('scanner-status');
    var statusSuccess = document.getElementById('status-success');
    var urlPreview = document.getElementById('url-preview');
    var readerDiv = document.getElementById('reader');
    var cameraSelect = document.getElementById('cameraSelect');
    var html5QrCode = new Html5Qrcode("reader");
    var scanning = false;

    function onScanSuccess(decodedText) {
        if (decodedText.startsWith('http')) {
            html5QrCode.stop().then(function() {
                scanning = false;
                readerDiv.style.display = 'none';
                scannerStatus.style.display = 'none';
                cameraSelect.parentElement.style.display = 'none';
                statusSuccess.style.display = 'block';
                urlPreview.textContent = decodedText;
                document.getElementById('qrUrl').value = decodedText;
                setTimeout(function() {
                    document.getElementById('qrForm').submit();
                }, 2000);
            });
        }
    }

    function startCamera(cameraId) {
        if (scanning) {
            html5QrCode.stop().then(function() {
                scanning = false;
                beginScan(cameraId);
            });
        } else {
            beginScan(cameraId);
        }
    }

    function beginScan(cameraId) {
        html5QrCode.start(
            cameraId,
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess,
            function() {}
        ).then(function() {
            scanning = true;
            scannerStatus.style.display = 'block';
            scannerStatus.textContent = 'Câmera ativa — aponte para o QR Code da nota fiscal';
        }).catch(function(err) {
            readerDiv.innerHTML =
                '<div class="alert alert-error">' +
                'Não foi possível acessar esta câmera.<br>' +
                '<small>' + err + '</small>' +
                '</div>';
        });
    }

    // Listar câmeras disponíveis
    Html5Qrcode.getCameras().then(function(cameras) {
        if (cameras && cameras.length > 0) {
            cameraSelect.innerHTML = '';

            cameras.forEach(function(camera, index) {
                var option = document.createElement('option');
                option.value = camera.id;
                option.textContent = camera.label || ('Câmera ' + (index + 1));
                cameraSelect.appendChild(option);
            });

            // Selecionar a última câmera (geralmente a externa/traseira)
            var lastCamera = cameras[cameras.length - 1];
            cameraSelect.value = lastCamera.id;
            startCamera(lastCamera.id);

            // Trocar câmera ao mudar seleção
            cameraSelect.addEventListener('change', function() {
                if (this.value) {
                    startCamera(this.value);
                }
            });
        } else {
            cameraSelect.innerHTML = '<option>Nenhuma câmera encontrada</option>';
            readerDiv.innerHTML =
                '<div class="alert alert-error">Nenhuma câmera detectada no dispositivo.</div>';
        }
    }).catch(function(err) {
        cameraSelect.innerHTML = '<option>Erro ao buscar câmeras</option>';
        readerDiv.innerHTML =
            '<div class="alert alert-error">' +
            'Não foi possível acessar as câmeras. Verifique as permissões do navegador.<br>' +
            '<small>' + err + '</small>' +
            '</div>';
    });
});
</script>
