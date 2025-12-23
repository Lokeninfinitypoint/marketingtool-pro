FROM node:20-alpine

# Install essentials
RUN apk add --no-cache git openssh bash curl

WORKDIR /app

# Copy package files
COPY package*.json ./
COPY tsconfig.json ./
COPY astro.config.mjs ./

# Install ALL dependencies (including devDependencies for Tailwind)
RUN npm ci

# Copy source files
COPY src ./src
COPY public ./public

# Build the application
RUN npm run build

# Expose port
EXPOSE 4321

# Start the preview server
CMD ["npm", "run", "preview", "--", "--host", "0.0.0.0", "--port", "4321"]
