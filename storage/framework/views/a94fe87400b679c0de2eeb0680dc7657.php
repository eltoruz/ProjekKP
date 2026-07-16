<div id="doc-viewer-modal" style="display:none;position:fixed;inset:0;z-index:100;align-items:center;justify-content:center;padding:1rem;background:rgba(0,0,0,0.6)">
    <div style="background:white;border-radius:12px;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);width:100%;max-width:900px;height:85vh;display:flex;flex-direction:column">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 24px;border-bottom:1px solid #e5e7eb">
            <span style="font-weight:600;color:#374151">Pratinjau Dokumen</span>
            <button onclick="closeDocViewer()" style="color:#9ca3af;font-size:1.25rem;line-height:1;background:none;border:none;cursor:pointer">&times;</button>
        </div>
        <iframe id="doc-viewer-frame" src="" style="flex:1;width:100%;border:none;border-radius:0 0 12px 12px"></iframe>
    </div>
</div>
<script>
document.addEventListener('click', function(e) {
    var btn = e.target.closest('[data-view-doc]');
    if (!btn) return;
    e.preventDefault();
    var url = btn.getAttribute('data-view-doc');
    document.getElementById('doc-viewer-frame').src = url;
    document.getElementById('doc-viewer-modal').style.display = 'flex';
});
window.closeDocViewer = function() {
    document.getElementById('doc-viewer-modal').style.display = 'none';
    document.getElementById('doc-viewer-frame').src = '';
};
document.getElementById('doc-viewer-modal').addEventListener('click', function(e) {
    if (e.target === this) closeDocViewer();
});
</script>
<?php /**PATH /home/eltoruz/ProjekKP/resources/views/filament/doc-viewer-modal.blade.php ENDPATH**/ ?>