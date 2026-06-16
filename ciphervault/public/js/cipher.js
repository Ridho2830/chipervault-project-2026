window.CipherVault = window.CipherVault || {};

// --- Encryption Logic ---

window.CipherVault.bufferToBase64 = function(buffer) {
    let binary = '';
    const bytes = new Uint8Array(buffer);
    const len = bytes.byteLength;
    for (let i = 0; i < len; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return window.btoa(binary);
};

window.CipherVault.base64ToBuffer = function(base64) {
    const binary = window.atob(base64);
    const len = binary.length;
    const bytes = new Uint8Array(len);
    for (let i = 0; i < len; i++) {
        bytes[i] = binary.charCodeAt(i);
    }
    return bytes.buffer;
};

window.CipherVault.deriveKey = async function(password, salt) {
    const enc = new TextEncoder();
    const keyMaterial = await window.crypto.subtle.importKey(
        "raw",
        enc.encode(password),
        { name: "PBKDF2" },
        false,
        ["deriveBits", "deriveKey"]
    );
    
    return window.crypto.subtle.deriveKey(
        {
            name: "PBKDF2",
            salt: salt,
            iterations: 310000,
            hash: "SHA-256"
        },
        keyMaterial,
        { name: "AES-GCM", length: 256 },
        false,
        ["encrypt", "decrypt"]
    );
};

window.CipherVault.encryptData = async function(data, password) {
    let buffer;
    if (typeof data === 'string') {
        const enc = new TextEncoder();
        buffer = enc.encode(data);
    } else {
        buffer = data;
    }

    const salt = window.crypto.getRandomValues(new Uint8Array(32));
    const iv = window.crypto.getRandomValues(new Uint8Array(12));
    
    const key = await window.CipherVault.deriveKey(password, salt);
    
    const encryptedBuffer = await window.crypto.subtle.encrypt(
        {
            name: "AES-GCM",
            iv: iv
        },
        key,
        buffer
    );
    
    return {
        ciphertext: window.CipherVault.bufferToBase64(encryptedBuffer),
        iv: window.CipherVault.bufferToBase64(iv),
        salt: window.CipherVault.bufferToBase64(salt)
    };
};

window.CipherVault.decryptData = async function(ciphertextBase64, ivBase64, saltBase64, password, returnString = true) {
    const ciphertextBuffer = window.CipherVault.base64ToBuffer(ciphertextBase64);
    const iv = window.CipherVault.base64ToBuffer(ivBase64);
    const salt = window.CipherVault.base64ToBuffer(saltBase64);
    
    const key = await window.CipherVault.deriveKey(password, salt);
    
    try {
        const decryptedBuffer = await window.crypto.subtle.decrypt(
            {
                name: "AES-GCM",
                iv: iv
            },
            key,
            ciphertextBuffer
        );
        
        if (returnString) {
            const dec = new TextDecoder();
            return dec.decode(decryptedBuffer);
        }
        
        return decryptedBuffer;
    } catch (e) {
        throw new Error("Decryption failed. Incorrect password or corrupted data.");
    }
};

// --- Password Logic ---

window.CipherVault.checkPasswordStrength = function(password) {
    if (!password) {
        return { score: 0, feedback: { warning: '', suggestions: [] } };
    }
    // Assumes zxcvbn is loaded globally via CDN
    if (typeof zxcvbn === 'function') {
        return zxcvbn(password);
    } else {
        console.warn("zxcvbn library not loaded.");
        return { score: 0, feedback: { warning: 'Library not loaded', suggestions: [] } };
    }
};

window.CipherVault.updateStrengthBar = function(passwordInputId, barId, textId) {
    const passwordInput = document.getElementById(passwordInputId);
    const bar = document.getElementById(barId);
    const text = document.getElementById(textId);
    
    if (!passwordInput || !bar || !text) return;
    
    passwordInput.addEventListener('input', (e) => {
        const val = e.target.value;
        const result = window.CipherVault.checkPasswordStrength(val);
        
        let color = 'bg-gray-700';
        let width = '0%';
        let label = 'Weak';
        
        if (val.length > 0) {
            switch(result.score) {
                case 0:
                case 1:
                    color = 'bg-red-500';
                    width = '25%';
                    label = 'Weak';
                    break;
                case 2:
                    color = 'bg-yellow-500';
                    width = '50%';
                    label = 'Fair';
                    break;
                case 3:
                    color = 'bg-blue-500';
                    width = '75%';
                    label = 'Good';
                    break;
                case 4:
                    color = 'bg-green-500';
                    width = '100%';
                    label = 'Strong';
                    break;
            }
        }
        
        bar.className = `h-2 rounded transition-all duration-300 ${color}`;
        bar.style.width = width;
        text.innerText = val.length > 0 ? `Password Strength: ${label}` : '';
        if (result.feedback.warning) {
            text.innerText += ` - ${result.feedback.warning}`;
        }
    });
};
