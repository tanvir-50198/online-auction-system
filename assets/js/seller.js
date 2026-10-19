/* Seller: live bid updates (works on dashboard and bid monitoring) */
(function () {
    'use strict';

    function setText(id, value) {
        var el = document.getElementById(id);
        if (el) el.textContent = value;
    }

    function fetchBidUpdates() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '../../ajax/seller_bid_updates.php', true);
        xhr.onload = function () {
            if (this.status !== 200) return;
            var listings;
            try { listings = JSON.parse(this.responseText); } catch (e) { return; }
            listings.forEach(function (l) {
                var price = Number(l.current_bid).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                setText('high-' + l.id, price);   // bid monitoring page
                setText('total-' + l.id, l.bid_count);
                setText('bid-' + l.id, price);    // dashboard cards
                setText('count-' + l.id, l.bid_count);
            });
        };
        xhr.send();
    }

    if (document.getElementById('liveBids') || document.getElementById('seller-dashboard')) {
        fetchBidUpdates();
        setInterval(fetchBidUpdates, 5000);
    }
})();
