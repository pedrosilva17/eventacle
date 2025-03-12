<h1 align="center"> Eventacle </h1>

<div align="center">
    <img src="public/images/eventacle-logo-small.png" alt="Eventacle Logo" width="250"/>
</div>
<br/>

Create events, predict their results and compete with your friends for the ultimate prize: bragging rights.

## About

**Eventacle** transforms prediction contests into a hassle-free, spending-free experience. Create events, track predictions, and settle once and for all who has the sharpest forecasting skills - all without losing a dime.

## Core Features

- **Create** - Set up your own events and define what others can predict
- **Predict** - Make predictions for upcoming events
- **Compete** - Join leaderboards and prove your prediction skills

## What Makes Eventacle Unique

- **No Financial Risk** - Pure entertainment without monetary involvement
- **Diverse Events** - Not limited to sports, predict anything
- **Inclusive Platform** - Suitable for all ages and interests

## Self-hosting

### Prerequisites

- Git
- Node.js/npm
- PHP/Composer

### Installation

1. Clone the repository:
```bash
git clone https://github.com/pedrosilva17/eventacle.git
cd eventacle
```

2. Configure environment variables:
```bash
cp .env.example .env
# Edit APP_ADMIN_EMAIL and APP_ADMIN_PASSWORD for your initial admin account
```

3. Build and start the application:
```bash
./build.sh && ./start.sh
```

4. Run migrations and seed the database:
```bash
./sail artisan migrate:fresh && ./sail artisan db:seed
```

The application should now be running at `http://localhost:3000`. Enjoy!

## The Name

**Eventacle** combines:
- **Event** - What is being predicted
- **Oracle** - _"someone who knows a lot about a subject and can give good advice"_ - prove to your friends you're that person!
- **Tentacle** - A nod to Paul the Octopus, the famous animal that correctly predicted multiple matches in the 2010 FIFA World Cup

Very creative, I know. But can you do better than Paul's 85% success rate?

---
_DISCAIMER: The emoji used in this README and as a logo on the app was created using [Emoji Kitchen](https://emoji.kitchen/). All rights for the emoji belong to their respective owners._