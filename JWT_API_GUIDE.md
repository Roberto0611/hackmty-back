# Guía de Autenticación JWT

## ✅ Cambios Realizados

### 1. **Deshabilitado CSRF**
- Comentado el middleware de Sanctum en `bootstrap/app.php`
- Esto elimina la validación CSRF que causaba el error 419

### 2. **Configuración JWT Pura**
- El login ahora retorna el token directamente en el JSON (no en cookies)
- CORS configurado con `supports_credentials: false`
- Todos los endpoints de autenticación funcionan sin sesiones

### 3. **CORS Abierto**
- `allowed_origins: ['*']` para desarrollo
- ⚠️ **IMPORTANTE**: En producción, especifica el dominio de tu frontend

## 🔐 Endpoints de Autenticación

### Registro
```
POST /api/register
Content-Type: application/json

{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}

Respuesta 201:
{
  "user": { ... },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

### Login
```
POST /api/login
Content-Type: application/json

{
  "email": "juan@example.com",
  "password": "password123"
}

Respuesta 200:
{
  "user": { ... },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

### Obtener Usuario Actual (Protegido)
```
GET /api/user
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...

Respuesta 200:
{
  "id": 1,
  "name": "Juan Pérez",
  "email": "juan@example.com",
  ...
}
```

### Logout (Protegido)
```
POST /api/logout
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...

Respuesta 200:
{
  "message": "Successfully logged out"
}
```

## 📱 Cómo Usar desde el Frontend

### JavaScript Fetch
```javascript
// Login
const login = async (email, password) => {
  const response = await fetch('http://localhost:8000/api/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ email, password })
  });
  
  const data = await response.json();
  
  // Guardar el token
  localStorage.setItem('token', data.token);
  
  return data;
};

// Hacer peticiones autenticadas
const getUser = async () => {
  const token = localStorage.getItem('token');
  
  const response = await fetch('http://localhost:8000/api/user', {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    }
  });
  
  return await response.json();
};

// Logout
const logout = async () => {
  const token = localStorage.getItem('token');
  
  await fetch('http://localhost:8000/api/logout', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    }
  });
  
  localStorage.removeItem('token');
};
```

### Axios
```javascript
import axios from 'axios';

// Configurar interceptor para agregar el token automáticamente
axios.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Login
const login = async (email, password) => {
  const { data } = await axios.post('http://localhost:8000/api/login', {
    email,
    password
  });
  
  localStorage.setItem('token', data.token);
  return data;
};

// Obtener usuario
const getUser = async () => {
  const { data } = await axios.get('http://localhost:8000/api/user');
  return data;
};
```

### React con Context
```javascript
// AuthContext.js
import React, { createContext, useState, useContext } from 'react';

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(localStorage.getItem('token'));

  const login = async (email, password) => {
    const response = await fetch('http://localhost:8000/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });
    
    const data = await response.json();
    
    if (response.ok) {
      setToken(data.token);
      setUser(data.user);
      localStorage.setItem('token', data.token);
    }
    
    return data;
  };

  const logout = async () => {
    if (token) {
      await fetch('http://localhost:8000/api/logout', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      });
    }
    
    setToken(null);
    setUser(null);
    localStorage.removeItem('token');
  };

  return (
    <AuthContext.Provider value={{ user, token, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
```

## 🔍 Solución de Problemas

### Error 419 (CSRF Token Mismatch)
✅ **RESUELTO** - El middleware de Sanctum ha sido deshabilitado

### Token no se acepta
- Verifica que estés enviando el header: `Authorization: Bearer {token}`
- Asegúrate de que el token no tenga espacios extra
- Revisa que JWT_SECRET esté configurado en `.env`

### CORS Errors
- Verifica que el backend esté corriendo
- La configuración actual acepta peticiones de cualquier origen (`*`)
- En producción, especifica tu dominio en `config/cors.php`

## 🚀 Para Producción

1. **Actualizar CORS** en `config/cors.php`:
```php
'allowed_origins' => [env('FRONTEND_URL', 'https://tuapp.com')],
'supports_credentials' => false,
```

2. **Variables de entorno** `.env`:
```
FRONTEND_URL=https://tuapp.com
JWT_SECRET=tu_secreto_super_seguro
JWT_TTL=60
```

3. **HTTPS Obligatorio**: Siempre usa HTTPS en producción

## 📝 Notas Adicionales

- Los tokens JWT expiran según `JWT_TTL` en minutos (default: 60)
- No se usan cookies ni sesiones
- El token debe ser almacenado por el cliente (localStorage, sessionStorage, etc.)
- Cada petición protegida debe incluir el header `Authorization: Bearer {token}`
