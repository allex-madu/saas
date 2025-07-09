# Dockerfile - frontend Vite
FROM node:20-alpine

# Diretório de trabalho
WORKDIR /app

# Copiar arquivos
COPY . .

# Instalar dependências
RUN npm install

# Expor porta padrão do Vite
EXPOSE 5173

# Rodar Vite em modo desenvolvimento
CMD ["npm", "run", "dev"]
