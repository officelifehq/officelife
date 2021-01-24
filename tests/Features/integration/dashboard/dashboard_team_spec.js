describe('Dashboard - teams', function () {
  it('should display an empty tab when not associated with a team', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.visit('/1/dashboard/me');
    cy.get('[data-cy=dashboard-team-tab]').click();

    cy.contains('You are not associated with a team at the moment');
  });

  it('should display the list of teams if the employee is associated with at least one team', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createTeam('product');
    cy.createTeam('sales');

    // assign a first team
    cy.visit('/1/employees/1');

    cy.get('[data-cy=open-team-modal-blank]').click();
    cy.get('[data-cy=list-team-1]').click();

    cy.visit('/1/dashboard');

    cy.get('[data-cy=dashboard-team-tab]').click();

    cy.contains('What your team has done this week');

    // assign a second team
    cy.visit('/1/employees/1');
    cy.get('[data-cy=open-team-modal]').click();
    cy.get('[data-cy=list-team-2]').click();

    cy.visit('/1/dashboard');

    cy.get('[data-cy=dashboard-team-tab]').click();

    cy.contains('What your team has done this week');
    cy.contains('Viewing');
    cy.contains('product');
    cy.contains('sales');

    // necessary so the next test actually works (cypress bug)
    cy.visit('/1/employees/1');
  });

  it('should display the upcoming birthdays of employees on the team dashboard', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.createTeam('product');

    cy.wait(1000);
    cy.assignEmployeeToTeam(1, 1);

    // visit the dashboard, the team tab and find that the birthday is empty
    cy.visit('/1/dashboard');
    cy.get('[data-cy=dashboard-team-tab]').click();
    cy.get('[data-cy=team-birthdate-blank]').should('exist');

    // edit the user birthdate
    var month = Cypress.moment().add(2, 'days').month() + 1;
    var day = Cypress.moment().add(2, 'days').date();
    cy.setBirthdate(1, 1, 'dwight', 'schrute', 'dwight@dundermifflin.com', 1981, month, day);

    // now, on the dashboard team tab, there should be a birthdate
    cy.visit('/1/dashboard');
    cy.get('[data-cy=dashboard-team-tab]').click();
    cy.get('[data-cy=team-birthdate-blank]').should('not.exist');
    cy.get('[data-cy=birthdays-list]').contains('dwight schrute');

    // change the birthdate and make sure the birthdate doesn't appear anymore
    // edit the user birthdate
    var month = Cypress.moment().add(40, 'days').month() + 1;
    var day = Cypress.moment().add(40, 'days').date();
    cy.setBirthdate(1, 1, 'dwight', 'schrute', 'dwight@dundermifflin.com', 1981, month, day);

    cy.visit('/1/dashboard');
    cy.get('[data-cy=dashboard-team-tab]').click();
    cy.get('[data-cy=team-birthdate-blank]').should('exist');

    // necessary so the next test actually works (cypress bug)
    cy.visit('/1/employees/1');
  });

  it('should display the employees of this team who work from home today', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.createTeam('product');

    cy.wait(1000);
    cy.assignEmployeeToTeam(1, 1);

    // visit the dashboard, the team tab and find that the birthday is empty
    cy.visit('/1/dashboard');
    cy.get('[data-cy=dashboard-team-tab]').click();
    cy.get('[data-cy=team-work-from-home-blank]').should('exist');

    // indicate that the employee works from home
    cy.visit('/1/dashboard/me');
    cy.get('[data-cy=log-from-work-home-cta]').check();

    // now, on the dashboard team tab, there should be the employee indicating
    // that he works from home
    cy.visit('/1/dashboard');
    cy.get('[data-cy=dashboard-team-tab]').click();
    cy.get('[data-cy=team-work-from-home-blank]').should('not.exist');
    cy.get('[data-cy=work-from-home-list]').contains('admin@admin.com');

    // necessary so the next test actually works (cypress bug)
    cy.visit('/1/employees/1');
  });

  it('should display upcoming new hires in this team', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createTeam('product');

    cy.wait(1000);
    cy.assignEmployeeToTeam(1, 1);

    // check that there are no futures hiring date
    cy.visit('/1/dashboard/team');
    cy.get('[data-cy=new-hires-list]').should('not.exist');

    // set the hiring date
    cy.setBirthdate(1, 1, 'dwight', 'schrute', 'dwight@dundermifflin.com', 1981, 3, 10);

    const tomorrowDate = Cypress.moment().add(1, 'days');
    var year = tomorrowDate.year();
    var month = tomorrowDate.month() + 1;
    var day = tomorrowDate.date();
    cy.setHiredDate(1, 1, year, month, day);
    cy.wait(1000);

    // visit the dashboard, the team tab and find that the birthday is empty
    cy.visit('/1/dashboard');
    cy.get('[data-cy=dashboard-team-tab]').click();
    cy.get('[data-cy=new-hires-list]').should('exist');
    cy.get('[data-cy=new-hires-list]').contains('dwight');

    // necessary so the next test actually works (cypress bug)
    cy.visit('/1/employees/1');
  });
});
