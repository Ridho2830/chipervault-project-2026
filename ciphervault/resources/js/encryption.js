// resources/js/encryption.js

/**
 * Utility for converting string/buffer to Base64 and vice versa
 */
export function bufferToBase64(buffer) {
    let binary = '';
    const bytes = new Uint8Array(buffer);
    const len = bytes.byteLength;
    for (let i = 0; i < len; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return window.btoa(binary);
}

export function base64ToBuffer(base64) {
    const binary = window.atob(base64);
    const len = binary.length;
    const bytes = new Uint8Array(len);
    for (let i = 0; i < len; i++) {
        bytes[i] = binary.charCodeAt(i);
    }
    return bytes.buffer;
}

/**
 * Derive an AES-256 key from a password and salt using PBKDF2
 * @param {string} password 
 * @param {Uint8Array} salt 
 * @returns {Promise<CryptoKey>}
 */
export async function deriveKey(password, salt) {
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
}

/**
 * Encrypt data using AES-256-GCM
 * @param {string|ArrayBuffer} data 
 * @param {string} password 
 * @returns {Promise<{ciphertext: string, iv: string, salt: string}>}
 */
export async function encryptData(data, password) {
    let buffer;
    if (typeof data === 'string') {
        const enc = new TextEncoder();
        buffer = enc.encode(data);
    } else {
        buffer = data;
    }

    const salt = window.crypto.getRandomValues(new Uint8Array(32));
    const iv = window.crypto.getRandomValues(new Uint8Array(12));
    
    const key = await deriveKey(password, salt);
    
    const encryptedBuffer = await window.crypto.subtle.encrypt(
        {
            name: "AES-GCM",
            iv: iv
        },
        key,
        buffer
    );
    
    return {
        ciphertext: bufferToBase64(encryptedBuffer),
        iv: bufferToBase64(iv),
        salt: bufferToBase64(salt)
    };
}

/**
 * Decrypt data using AES-256-GCM
 * @param {string} ciphertextBase64 
 * @param {string} ivBase64 
 * @param {string} saltBase64 
 * @param {string} password 
 * @param {boolean} returnString - If true, decodes as string. If false, returns ArrayBuffer.
 * @returns {Promise<string|ArrayBuffer>}
 */
export async function decryptData(ciphertextBase64, ivBase64, saltBase64, password, returnString = true) {
    const ciphertextBuffer = base64ToBuffer(ciphertextBase64);
    const iv = base64ToBuffer(ivBase64);
    const salt = base64ToBuffer(saltBase64);
    
    const key = await deriveKey(password, salt);
    
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
}
