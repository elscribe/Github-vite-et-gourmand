use('vite_gourmand');

db.menu_statistics.drop();
db.monthly_statistics.drop();
db.dashboard_statistics.drop();

const collections = db.getCollectionNames();

if (!collections.includes('menu_statistics')) {
  db.createCollection('menu_statistics', {
    validator: {
      $jsonSchema: {
        bsonType: 'object',
        required: [
          'menuId',
          'menuTitle',
          'orders',
          'revenue',
          'averageBasket',
          'averageRating',
          'lastOrder',
          'updatedAt'
        ],
        properties: {
          menuId: {
            bsonType: ['int', 'long'],
            description: 'SQL menu identifier'
          },
          menuTitle: {
            bsonType: 'string',
            description: 'Menu title copied from the SQL source'
          },
          orders: {
            bsonType: ['int', 'long'],
            minimum: 0,
            description: 'Number of orders for this menu'
          },
          revenue: {
            bsonType: ['int', 'long', 'double', 'decimal'],
            minimum: 0,
            description: 'Total revenue for this menu'
          },
          averageBasket: {
            bsonType: ['int', 'long', 'double', 'decimal'],
            minimum: 0,
            description: 'Average order amount for this menu'
          },
          averageRating: {
            bsonType: ['int', 'long', 'double', 'decimal'],
            minimum: 0,
            maximum: 5,
            description: 'Average validated customer rating'
          },
          lastOrder: {
            bsonType: 'date',
            description: 'Date of the last order for this menu'
          },
          updatedAt: {
            bsonType: 'date',
            description: 'Last aggregation update date'
          }
        }
      }
    },
    validationLevel: 'strict',
    validationAction: 'error'
  });
} else {
  db.runCommand({
    collMod: 'menu_statistics',
    validator: {
      $jsonSchema: {
        bsonType: 'object',
        required: [
          'menuId',
          'menuTitle',
          'orders',
          'revenue',
          'averageBasket',
          'averageRating',
          'lastOrder',
          'updatedAt'
        ],
        properties: {
          menuId: { bsonType: ['int', 'long'] },
          menuTitle: { bsonType: 'string' },
          orders: { bsonType: ['int', 'long'], minimum: 0 },
          revenue: { bsonType: ['int', 'long', 'double', 'decimal'], minimum: 0 },
          averageBasket: { bsonType: ['int', 'long', 'double', 'decimal'], minimum: 0 },
          averageRating: { bsonType: ['int', 'long', 'double', 'decimal'], minimum: 0, maximum: 5 },
          lastOrder: { bsonType: 'date' },
          updatedAt: { bsonType: 'date' }
        }
      }
    },
    validationLevel: 'strict',
    validationAction: 'error'
  });
}

if (!collections.includes('monthly_statistics')) {
  db.createCollection('monthly_statistics', {
    validator: {
      $jsonSchema: {
        bsonType: 'object',
        required: [
          'month',
          'revenue',
          'orders',
          'averageBasket',
          'bestSellingMenu',
          'updatedAt'
        ],
        properties: {
          month: {
            bsonType: 'string',
            pattern: '^\\d{4}-\\d{2}$',
            description: 'Aggregation month formatted as YYYY-MM'
          },
          revenue: {
            bsonType: ['int', 'long', 'double', 'decimal'],
            minimum: 0,
            description: 'Monthly revenue'
          },
          orders: {
            bsonType: ['int', 'long'],
            minimum: 0,
            description: 'Monthly order count'
          },
          averageBasket: {
            bsonType: ['int', 'long', 'double', 'decimal'],
            minimum: 0,
            description: 'Monthly average basket'
          },
          bestSellingMenu: {
            bsonType: 'string',
            description: 'Best-selling menu title for the month'
          },
          updatedAt: {
            bsonType: 'date',
            description: 'Last aggregation update date'
          }
        }
      }
    },
    validationLevel: 'strict',
    validationAction: 'error'
  });
} else {
  db.runCommand({
    collMod: 'monthly_statistics',
    validator: {
      $jsonSchema: {
        bsonType: 'object',
        required: [
          'month',
          'revenue',
          'orders',
          'averageBasket',
          'bestSellingMenu',
          'updatedAt'
        ],
        properties: {
          month: { bsonType: 'string', pattern: '^\\d{4}-\\d{2}$' },
          revenue: { bsonType: ['int', 'long', 'double', 'decimal'], minimum: 0 },
          orders: { bsonType: ['int', 'long'], minimum: 0 },
          averageBasket: { bsonType: ['int', 'long', 'double', 'decimal'], minimum: 0 },
          bestSellingMenu: { bsonType: 'string' },
          updatedAt: { bsonType: 'date' }
        }
      }
    },
    validationLevel: 'strict',
    validationAction: 'error'
  });
}

if (!collections.includes('dashboard_statistics')) {
  db.createCollection('dashboard_statistics', {
    validator: {
      $jsonSchema: {
        bsonType: 'object',
        required: [
          'generatedAt',
          'totalRevenue',
          'totalOrders',
          'activeMenus',
          'topMenu',
          'averageBasket',
          'averageRating'
        ],
        properties: {
          generatedAt: {
            bsonType: 'date',
            description: 'Dashboard snapshot generation date'
          },
          totalRevenue: {
            bsonType: ['int', 'long', 'double', 'decimal'],
            minimum: 0,
            description: 'Revenue visible in the dashboard snapshot'
          },
          totalOrders: {
            bsonType: ['int', 'long'],
            minimum: 0,
            description: 'Order count visible in the dashboard snapshot'
          },
          activeMenus: {
            bsonType: ['int', 'long'],
            minimum: 0,
            description: 'Number of active SQL menus'
          },
          topMenu: {
            bsonType: 'string',
            description: 'Best-performing menu title'
          },
          averageBasket: {
            bsonType: ['int', 'long', 'double', 'decimal'],
            minimum: 0,
            description: 'Average basket in the dashboard snapshot'
          },
          averageRating: {
            bsonType: ['int', 'long', 'double', 'decimal'],
            minimum: 0,
            maximum: 5,
            description: 'Average customer rating in the dashboard snapshot'
          }
        }
      }
    },
    validationLevel: 'strict',
    validationAction: 'error'
  });
} else {
  db.runCommand({
    collMod: 'dashboard_statistics',
    validator: {
      $jsonSchema: {
        bsonType: 'object',
        required: [
          'generatedAt',
          'totalRevenue',
          'totalOrders',
          'activeMenus',
          'topMenu',
          'averageBasket',
          'averageRating'
        ],
        properties: {
          generatedAt: { bsonType: 'date' },
          totalRevenue: { bsonType: ['int', 'long', 'double', 'decimal'], minimum: 0 },
          totalOrders: { bsonType: ['int', 'long'], minimum: 0 },
          activeMenus: { bsonType: ['int', 'long'], minimum: 0 },
          topMenu: { bsonType: 'string' },
          averageBasket: { bsonType: ['int', 'long', 'double', 'decimal'], minimum: 0 },
          averageRating: { bsonType: ['int', 'long', 'double', 'decimal'], minimum: 0, maximum: 5 }
        }
      }
    },
    validationLevel: 'strict',
    validationAction: 'error'
  });
}

db.menu_statistics.createIndex({ menuId: 1 }, { unique: true });
db.menu_statistics.createIndex({ revenue: -1 });
db.menu_statistics.createIndex({ updatedAt: -1 });

db.monthly_statistics.createIndex({ month: 1 }, { unique: true });
db.monthly_statistics.createIndex({ revenue: -1 });

db.dashboard_statistics.createIndex({ generatedAt: -1 });
db.dashboard_statistics.createIndex({ topMenu: 1 });
