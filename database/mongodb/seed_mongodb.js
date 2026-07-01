use('vite_gourmand');

db.menu_statistics.deleteMany({});
db.monthly_statistics.deleteMany({});
db.dashboard_statistics.deleteMany({});

db.menu_statistics.insertMany([
  {
    menuId: NumberInt(1),
    menuTitle: 'Christmas Menu',
    orders: NumberInt(38),
    revenue: 29990.00,
    averageBasket: 789.21,
    averageRating: 4.8,
    lastOrder: ISODate('2026-12-24T18:30:00Z'),
    updatedAt: ISODate('2026-12-31T23:30:00Z')
  },
  {
    menuId: NumberInt(2),
    menuTitle: 'Wedding Menu',
    orders: NumberInt(18),
    revenue: 52320.00,
    averageBasket: 2906.67,
    averageRating: 4.9,
    lastOrder: ISODate('2026-09-12T17:00:00Z'),
    updatedAt: ISODate('2026-12-31T23:30:00Z')
  },
  {
    menuId: NumberInt(3),
    menuTitle: 'Prestige Menu',
    orders: NumberInt(32),
    revenue: 38278.61,
    averageBasket: 1196.21,
    averageRating: 4.6,
    lastOrder: ISODate('2026-07-18T20:00:00Z'),
    updatedAt: ISODate('2026-12-31T23:30:00Z')
  },
  {
    menuId: NumberInt(4),
    menuTitle: 'Vegetarian Menu',
    orders: NumberInt(26),
    revenue: 8254.63,
    averageBasket: 317.49,
    averageRating: 4.4,
    lastOrder: ISODate('2026-06-18T12:30:00Z'),
    updatedAt: ISODate('2026-12-31T23:30:00Z')
  },
  {
    menuId: NumberInt(5),
    menuTitle: 'Family Menu',
    orders: NumberInt(45),
    revenue: 13876.55,
    averageBasket: 308.37,
    averageRating: 4.5,
    lastOrder: ISODate('2026-06-15T13:00:00Z'),
    updatedAt: ISODate('2026-12-31T23:30:00Z')
  },
  {
    menuId: NumberInt(6),
    menuTitle: 'Cocktail Menu',
    orders: NumberInt(32),
    revenue: 42262.00,
    averageBasket: 1320.69,
    averageRating: 4.7,
    lastOrder: ISODate('2026-06-25T18:00:00Z'),
    updatedAt: ISODate('2026-12-31T23:30:00Z')
  }
]);

db.monthly_statistics.insertMany([
  {
    month: '2026-01',
    revenue: 2740.00,
    orders: NumberInt(5),
    averageBasket: 548.00,
    bestSellingMenu: 'Family Menu',
    updatedAt: ISODate('2026-01-31T23:30:00Z')
  },
  {
    month: '2026-02',
    revenue: 4360.00,
    orders: NumberInt(7),
    averageBasket: 622.86,
    bestSellingMenu: 'Prestige Menu',
    updatedAt: ISODate('2026-02-28T23:30:00Z')
  },
  {
    month: '2026-03',
    revenue: 7386.60,
    orders: NumberInt(14),
    averageBasket: 527.61,
    bestSellingMenu: 'Family Menu',
    updatedAt: ISODate('2026-03-31T23:30:00Z')
  },
  {
    month: '2026-04',
    revenue: 11875.50,
    orders: NumberInt(16),
    averageBasket: 742.22,
    bestSellingMenu: 'Cocktail Menu',
    updatedAt: ISODate('2026-04-30T23:30:00Z')
  },
  {
    month: '2026-05',
    revenue: 17328.12,
    orders: NumberInt(21),
    averageBasket: 825.15,
    bestSellingMenu: 'Wedding Menu',
    updatedAt: ISODate('2026-05-31T23:30:00Z')
  },
  {
    month: '2026-06',
    revenue: 22108.84,
    orders: NumberInt(28),
    averageBasket: 789.60,
    bestSellingMenu: 'Cocktail Menu',
    updatedAt: ISODate('2026-06-30T23:30:00Z')
  },
  {
    month: '2026-07',
    revenue: 19642.26,
    orders: NumberInt(20),
    averageBasket: 982.11,
    bestSellingMenu: 'Prestige Menu',
    updatedAt: ISODate('2026-07-31T23:30:00Z')
  },
  {
    month: '2026-08',
    revenue: 15340.00,
    orders: NumberInt(13),
    averageBasket: 1180.00,
    bestSellingMenu: 'Wedding Menu',
    updatedAt: ISODate('2026-08-31T23:30:00Z')
  },
  {
    month: '2026-09',
    revenue: 28640.00,
    orders: NumberInt(18),
    averageBasket: 1591.11,
    bestSellingMenu: 'Wedding Menu',
    updatedAt: ISODate('2026-09-30T23:30:00Z')
  },
  {
    month: '2026-10',
    revenue: 12175.00,
    orders: NumberInt(12),
    averageBasket: 1014.58,
    bestSellingMenu: 'Prestige Menu',
    updatedAt: ISODate('2026-10-31T23:30:00Z')
  },
  {
    month: '2026-11',
    revenue: 20975.47,
    orders: NumberInt(19),
    averageBasket: 1103.97,
    bestSellingMenu: 'Christmas Menu',
    updatedAt: ISODate('2026-11-30T23:30:00Z')
  },
  {
    month: '2026-12',
    revenue: 22410.00,
    orders: NumberInt(18),
    averageBasket: 1245.00,
    bestSellingMenu: 'Christmas Menu',
    updatedAt: ISODate('2026-12-31T23:30:00Z')
  }
]);

db.dashboard_statistics.insertMany([
  {
    generatedAt: ISODate('2026-01-31T23:45:00Z'),
    totalRevenue: 2740.00,
    totalOrders: NumberInt(5),
    activeMenus: NumberInt(6),
    topMenu: 'Family Menu',
    averageBasket: 548.00,
    averageRating: 4.3
  },
  {
    generatedAt: ISODate('2026-02-28T23:45:00Z'),
    totalRevenue: 4360.00,
    totalOrders: NumberInt(7),
    activeMenus: NumberInt(6),
    topMenu: 'Prestige Menu',
    averageBasket: 622.86,
    averageRating: 4.4
  },
  {
    generatedAt: ISODate('2026-03-31T23:45:00Z'),
    totalRevenue: 7386.60,
    totalOrders: NumberInt(14),
    activeMenus: NumberInt(6),
    topMenu: 'Family Menu',
    averageBasket: 527.61,
    averageRating: 4.5
  },
  {
    generatedAt: ISODate('2026-04-30T23:45:00Z'),
    totalRevenue: 11875.50,
    totalOrders: NumberInt(16),
    activeMenus: NumberInt(6),
    topMenu: 'Cocktail Menu',
    averageBasket: 742.22,
    averageRating: 4.6
  },
  {
    generatedAt: ISODate('2026-05-31T23:45:00Z'),
    totalRevenue: 17328.12,
    totalOrders: NumberInt(21),
    activeMenus: NumberInt(6),
    topMenu: 'Wedding Menu',
    averageBasket: 825.15,
    averageRating: 4.6
  },
  {
    generatedAt: ISODate('2026-06-30T23:45:00Z'),
    totalRevenue: 22108.84,
    totalOrders: NumberInt(28),
    activeMenus: NumberInt(6),
    topMenu: 'Cocktail Menu',
    averageBasket: 789.60,
    averageRating: 4.7
  },
  {
    generatedAt: ISODate('2026-07-31T23:45:00Z'),
    totalRevenue: 19642.26,
    totalOrders: NumberInt(20),
    activeMenus: NumberInt(6),
    topMenu: 'Prestige Menu',
    averageBasket: 982.11,
    averageRating: 4.6
  },
  {
    generatedAt: ISODate('2026-08-31T23:45:00Z'),
    totalRevenue: 15340.00,
    totalOrders: NumberInt(13),
    activeMenus: NumberInt(6),
    topMenu: 'Wedding Menu',
    averageBasket: 1180.00,
    averageRating: 4.8
  },
  {
    generatedAt: ISODate('2026-09-30T23:45:00Z'),
    totalRevenue: 28640.00,
    totalOrders: NumberInt(18),
    activeMenus: NumberInt(6),
    topMenu: 'Wedding Menu',
    averageBasket: 1591.11,
    averageRating: 4.9
  },
  {
    generatedAt: ISODate('2026-10-31T23:45:00Z'),
    totalRevenue: 12175.00,
    totalOrders: NumberInt(12),
    activeMenus: NumberInt(6),
    topMenu: 'Prestige Menu',
    averageBasket: 1014.58,
    averageRating: 4.6
  },
  {
    generatedAt: ISODate('2026-11-30T23:45:00Z'),
    totalRevenue: 20975.47,
    totalOrders: NumberInt(19),
    activeMenus: NumberInt(6),
    topMenu: 'Christmas Menu',
    averageBasket: 1103.97,
    averageRating: 4.7
  },
  {
    generatedAt: ISODate('2026-12-31T23:45:00Z'),
    totalRevenue: 22410.00,
    totalOrders: NumberInt(18),
    activeMenus: NumberInt(6),
    topMenu: 'Christmas Menu',
    averageBasket: 1245.00,
    averageRating: 4.8
  }
]);
