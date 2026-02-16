<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Store Inventory</title>
    <style>
        body{font-family:system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial;background:#012158;color:#fff;display:flex;min-height:100vh;align-items:flex-start;justify-content:center;padding:40px}
        .card-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}
        .logo{width:84px;height:auto}
        .card{max-width:820px;width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.06);padding:24px;border-radius:12px}
        h1{font-size:20px;margin:0 0 12px}
        h2{font-size:16px;margin:18px 0 8px;color:#ffd66b}
        .card-header .header-left{display:flex;flex-direction:column}
        .balance{margin-top:8px;display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.03);padding:6px 10px;border-radius:10px;color:#dff1ff}
        .item{margin:10px 0;padding:10px;border-radius:8px;background:rgba(255,255,255,0.02);cursor:pointer;transition:all 0.2s ease;position:relative}
        .item:hover{background:rgba(255,255,255,0.08);transform:translateY(-2px)}
        .item.purchased{border:1px solid rgba(74,222,128,0.3);background:rgba(74,222,128,0.05)}
        .item.purchased::after{content:'✓ Owned';position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:12px;color:#4ade80;background:rgba(74,222,128,0.15);padding:2px 8px;border-radius:4px}
        .cost{float:right;color:#cfe9ff;font-weight:600}
        .muted{color:#a9c7ff;font-size:13px}
        ul{margin:8px 0 0 18px}
        .return-link{max-width:820px;width:100%;text-align:center;margin-top:14px}
        .return-link a{color:#cfe9ff;text-decoration:underline}
        
        /* Modal Styles */
        .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:50;display:none;align-items:center;justify-content:center}
        .modal-overlay.active{display:flex}
        .modal{background:#012158;border:1px solid rgba(255,255,255,0.15);border-radius:12px;padding:24px;width:90%;max-width:420px}
        .modal-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
        .modal-title{font-size:18px;font-weight:600;color:#fff;margin:0}
        .modal-close{background:transparent;border:none;color:#a9c7ff;cursor:pointer;font-size:14px;padding:4px 8px;border-radius:4px;transition:background 0.2s}
        .modal-close:hover{background:rgba(255,255,255,0.1)}
        .modal-body{margin-bottom:20px}
        .modal-description{color:#dff1ff;margin:0 0 12px;line-height:1.5}
        .modal-cost{color:#ffd66b;font-weight:600;font-size:16px;margin:0}
        .modal-result{margin-bottom:16px;padding:12px;border-radius:8px;display:none;font-size:14px}
        .modal-result.success{display:block;background:rgba(74,222,128,0.1);color:#4ade80}
        .modal-result.error{display:block;background:rgba(248,113,113,0.1);color:#f87171}
        .modal-actions{display:flex;gap:12px;justify-content:center}
        .btn{padding:10px 24px;border-radius:8px;cursor:pointer;font-weight:600;transition:all 0.2s;font-size:14px}
        .btn-buy{border:1px solid #4ade80;background:rgba(74,222,128,0.1);color:#4ade80}
        .btn-buy:hover{background:rgba(74,222,128,0.2)}
        .btn-cancel{border:1px solid rgba(255,255,255,0.2);background:transparent;color:#dff1ff}
        .btn-cancel:hover{background:rgba(255,255,255,0.05)}
        /* Fix dropdown option visibility */
        #playerSelect option { background-color: #012158; color: #fff; }
    </style>
</head>
<body>
    <main class="card">
        <div class="card-header">
            <div class="header-left">
                <h1>STORE INVENTORY</h1>
                <div style="margin-bottom: 8px;">
                    <select id="playerSelect" style="background:#012158;border:1px solid rgba(255,255,255,0.2);color:#fff;padding:4px 8px;border-radius:6px;outline:none;cursor:pointer">
                        <option value="1">Player 1</option>
                        <option value="2">Player 2</option>
                        <option value="3">Player 3</option>
                        <option value="4">Player 4</option>
                    </select>
                </div>
                <div id="tokenBalance" class="balance">SDG Tokens: <strong id="balanceAmount">0</strong></div>
            </div>
            <img src="assets/bp.png" alt="Game Logo" class="logo" />
        </div>

        <h2>VEHICLES</h2>
        <div class="item" data-item-id="vehicle">
            <div>3 SDG Tokens Each<span class="cost">3 SDG</span></div>
        </div>

        <h2>OPPORTUNITY CARD</h2>
        <div class="item" data-item-id="opportunity">
            <div>-2 SDG Tokens<span class="cost">-2 SDG</span></div>
        </div>

        <h2>UPGRADES</h2>

        <div class="item" data-item-id="engineering">
            <div><strong>Engineering Toolkit</strong><span class="cost">2 SDG Tokens</span></div>
            <div class="muted">When you get an opportunity card wrong, you get another chance to get the correct answer. (Can only be used once)</div>
        </div>

        <div class="item" data-item-id="smartPlanner">
            <div><strong>Smart Planner</strong><span class="cost">3 SDG Tokens</span></div>
            <div class="muted">When rolling the die, you may add or subtract by 1 the number you received. (Can only be used once)</div>
        </div>

        <div class="item" data-item-id="innovation">
            <div><strong>Innovation Pass</strong><span class="cost">4 SDG Tokens</span></div>
            <div class="muted">When you answer an opportunity card correctly, you may grab one additional innovation card. (Can only be used once)</div>
        </div>

        <div class="item" data-item-id="riskBuffer">
            <div><strong>Risk Buffer</strong><span class="cost">2 SDG Tokens</span></div>
            <div class="muted">Ignore the effect of one Crisis Card. (Can only be used once)</div>
        </div>

        <div class="item" data-item-id="globalPartnership">
            <div><strong>Global Partnership Pass</strong><span class="cost">5 SDG Tokens</span></div>
            <div class="muted">Reuse a one-time item</div>
        </div>

        <div class="return-link">
            <a href="index.html">Return to homepage</a>
        </div>
    </main>

    
    <div id="itemModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2 id="modalTitle" class="modal-title">Item Details</h2>
                <button id="closeModal" class="modal-close">✕ Close</button>
            </div>
            <div class="modal-body">
                <p id="modalDescription" class="modal-description"></p>
                <p id="modalCost" class="modal-cost"></p>
            </div>
            <div id="modalResult" class="modal-result"></div>
            <div class="modal-actions">
                <button id="buyBtn" class="btn btn-buy">Purchase</button>
                <button id="cancelBtn" class="btn btn-cancel">Cancel</button>
            </div>
        </div>
    </div>

<script>
(function() {
    'use strict';
    
    const PLAYER_KEY = 'blue_game_players_v1';
    let currentPlayerId = 1;
    
    
    const shopItems = {
        'vehicle': { 
            id: 'vehicle', 
            name: 'Vehicle', 
            description: 'A vehicle to help you travel across the game board. Use it to move faster and reach your goals.', 
            cost: 3 
        },
        'opportunity': { 
            id: 'opportunity', 
            name: 'Opportunity Card', 
            description: 'A chance card that can either reward or challenge you. Answer correctly to earn tokens!', 
            cost: -2 
        },
        'engineering': { 
            id: 'engineering', 
            name: 'Engineering Toolkit', 
            description: 'When you get an opportunity card wrong, you get another chance to get the correct answer. (Can only be used once)', 
            cost: 2 
        },
        'smartPlanner': { 
            id: 'smartPlanner', 
            name: 'Smart Planner', 
            description: 'When rolling the die, you may add or subtract by 1 the number you received. (Can only be used once)', 
            cost: 3 
        },
        'innovation': { 
            id: 'innovation', 
            name: 'Innovation Pass', 
            description: 'When you answer an opportunity card correctly, you may grab one additional innovation card. (Can only be used once)', 
            cost: 4 
        },
        'riskBuffer': { 
            id: 'riskBuffer', 
            name: 'Risk Buffer', 
            description: 'Ignore the effect of one Crisis Card. (Can only be used once)', 
            cost: 2 
        },
        'globalPartnership': { 
            id: 'globalPartnership', 
            name: 'Global Partnership Pass', 
            description: 'Reuse a one-time item. (Can only be used once)', 
            cost: 5 
        }
    };

   
    const itemModal = document.getElementById('itemModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    const modalCost = document.getElementById('modalCost');
    const modalResult = document.getElementById('modalResult');
    const closeModal = document.getElementById('closeModal');
    const buyBtn = document.getElementById('buyBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const balEl = document.getElementById('balanceAmount');
    const playerSelect = document.getElementById('playerSelect');

    let currentItem = null;

    function loadPlayers() {
        try {
            const raw = localStorage.getItem(PLAYER_KEY);
            if (!raw) return {1: {sdg: 0, sp: 0, inventory: []}, 2: {sdg: 0, sp: 0, inventory: []}, 3: {sdg: 0, sp: 0, inventory: []}, 4: {sdg: 0, sp: 0, inventory: []}};
            const parsed = JSON.parse(raw);
            // Ensure structure exists for all players
            for(let i=1; i<=4; i++) {
                if(!parsed[i]) parsed[i] = {sdg: 0, sp: 0, inventory: []};
                if(!parsed[i].inventory) parsed[i].inventory = [];
                if(typeof parsed[i].sdg === 'undefined') parsed[i].sdg = 0;
            }
            return parsed;
        } catch(e) {
            console.error(e);
            return {1: {sdg: 0, sp: 0, inventory: []}, 2: {sdg: 0, sp: 0, inventory: []}, 3: {sdg: 0, sp: 0, inventory: []}, 4: {sdg: 0, sp: 0, inventory: []}};
        }
    }

    function savePlayers(players) {
        localStorage.setItem(PLAYER_KEY, JSON.stringify(players));
    }
    
    
    function updateBalanceDisplay() {
        const players = loadPlayers();
        const p = players[currentPlayerId];
        balEl.textContent = p ? p.sdg : 0;
    }

   
    function updatePurchasedItems() {
        const players = loadPlayers();
        const purchased = players[currentPlayerId].inventory || [];
        
        document.querySelectorAll('.item[data-item-id]').forEach(item => {
            const itemId = item.dataset.itemId;
            if (purchased.includes(itemId)) {
                item.classList.add('purchased');
            } else {
                item.classList.remove('purchased');
            }
        });
    }

    
    function init() {
        // Listen for player change
        playerSelect.addEventListener('change', (e) => {
            currentPlayerId = parseInt(e.target.value);
            updateBalanceDisplay();
            updatePurchasedItems();
        });

        updateBalanceDisplay();
        updatePurchasedItems();
    }

   
    function openModal(item) {
        currentItem = item;
        modalTitle.textContent = item.name;
        modalDescription.textContent = item.description;
        
        if (item.cost < 0) {
            modalCost.textContent = 'Reward: ' + Math.abs(item.cost) + ' SDG Tokens';
        } else {
            modalCost.textContent = 'Cost: ' + item.cost + ' SDG Tokens';
        }
        
        modalResult.className = 'modal-result';
        modalResult.textContent = '';
        
       
        const players = loadPlayers();
        const purchased = players[currentPlayerId].inventory || [];
        
        if (purchased.includes(item.id) && item.cost > 0) {
            buyBtn.style.display = 'none';
            modalResult.className = 'modal-result success';
            modalResult.textContent = '✓ You already own this item!';
        } else {
            buyBtn.style.display = 'inline-block';
        }
        
        itemModal.classList.add('active');
    }

  
    function closeItemModal() {
        itemModal.classList.remove('active');
        currentItem = null;
    }

   
    function handlePurchase() {
        if (!currentItem) return;
        
        const players = loadPlayers();
        const player = players[currentPlayerId];
        const balance = player.sdg;
        const cost = currentItem.cost;
        
        if (cost > 0) {
            
            if (balance >= cost) {
                player.sdg = balance - cost;
                if (!player.inventory.includes(currentItem.id)) {
                    player.inventory.push(currentItem.id);
                }
                savePlayers(players);
                
                updateBalanceDisplay();
                updatePurchasedItems();
                
                modalResult.className = 'modal-result success';
                modalResult.textContent = '✓ Successfully purchased ' + currentItem.name + '!';
                buyBtn.style.display = 'none';
            } else {
                modalResult.className = 'modal-result error';
                modalResult.textContent = '✗ Not enough tokens! You need ' + cost + ' tokens but only have ' + balance + '.';
            }
        } else if (cost < 0) {
            
            player.sdg = balance + Math.abs(cost);
            savePlayers(players);
            updateBalanceDisplay();
            
            modalResult.className = 'modal-result success';
            modalResult.textContent = '✓ You earned ' + Math.abs(cost) + ' SDG Tokens!';
            buyBtn.style.display = 'none';
        } else {
            
            modalResult.className = 'modal-result success';
            modalResult.textContent = '✓ Item obtained!';
            buyBtn.style.display = 'none';
        }
    }

    
    document.querySelectorAll('.item[data-item-id]').forEach(item => {
        item.addEventListener('click', () => {
            const itemId = item.dataset.itemId;
            if (shopItems[itemId]) {
                openModal(shopItems[itemId]);
            }
        });
    });


    closeModal.addEventListener('click', closeItemModal);
    cancelBtn.addEventListener('click', closeItemModal);
    buyBtn.addEventListener('click', handlePurchase);

    
    itemModal.addEventListener('click', (e) => {
        if (e.target === itemModal) {
            closeItemModal();
        }
    });

    
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && itemModal.classList.contains('active')) {
            closeItemModal();
        }
    });

    

    init();
})();
</script>
</body>
</html>
