import './bootstrap';
import { encryptData, decryptData, bufferToBase64, base64ToBuffer } from './encryption';
import { checkPasswordStrength, updateStrengthBar } from './password';

window.CipherVault = {
    encryptData,
    decryptData,
    bufferToBase64,
    base64ToBuffer,
    checkPasswordStrength,
    updateStrengthBar
};
