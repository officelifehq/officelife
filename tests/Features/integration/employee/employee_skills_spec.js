describe('Employee - skills', function () {
  it('should let an admin manage a skill on an employee profile page', function () {
    cy.login()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)

    cy.visit('/1/employees/1')

    // create first skill
    cy.get('[data-cy=manage-skill-button]').click()
    cy.get('[data-cy=search-skill]').type('php')
    cy.get('[data-cy=submit-create-skill]').click()
    cy.get('[data-cy=existing-skills-list-item-1]').should('exist')
    cy.get('[data-cy=cancel-add-skill]').click()
    cy.get('[data-cy=non-edit-skill-list-item-1]').contains('php')
    cy.hasAuditLog('Associated the skill called php', '/1/employees/1')
    cy.hasEmployeeLog('Has been associated with the skill called php', '/1/employees/1')

    // create skill with same name as above
    // it shouldn't let me create the skill
    cy.get('[data-cy=manage-skill-button]').click()
    cy.get('[data-cy=search-skill]').type('php')
    cy.get('[data-cy=skill-already-in-list]').should('exist')
    cy.get('[data-cy=cancel-add-skill').click()

    // go to second employee and add the same skill
    cy.visit('/1/employees/2')
    cy.get('[data-cy=manage-skill-button]').click()
    cy.get('[data-cy=search-skill]').type('php')
    cy.get('[data-cy=skill-name-1]').click()
    cy.get('[data-cy=existing-skills-list-item-1]').should('exist')
    cy.get('[data-cy=cancel-add-skill]').click()
    cy.get('[data-cy=non-edit-skill-list-item-1]').contains('php')
    cy.hasAuditLog('Associated the skill called php', '/1/employees/1')

    // now remove the php skill from the first employee
    cy.visit('/1/employees/1')
    cy.get('[data-cy=manage-skill-button]').click()
    cy.get('[data-cy=existing-skills-list-item-remove-1]').click()
    cy.get('[data-cy=cancel-add-skill').click()
    cy.get('[data-cy=skill-list-blank').should('exist')
    cy.hasAuditLog('Removed the skill called php', '/1/employees/1')
  })

  it('should let an HR manage a skill on an employee profile page', function () {
    cy.login()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)

    cy.changePermission(1, 200)

    cy.visit('/1/employees/1')

    // create first skill
    cy.get('[data-cy=manage-skill-button]').click()
    cy.get('[data-cy=search-skill]').type('php')
    cy.get('[data-cy=submit-create-skill]').click()
    cy.get('[data-cy=existing-skills-list-item-1]').should('exist')
    cy.get('[data-cy=cancel-add-skill]').click()
    cy.get('[data-cy=non-edit-skill-list-item-1]').contains('php')

    // create skill with same name as above
    // it shouldn't let me create the skill
    cy.get('[data-cy=manage-skill-button]').click()
    cy.get('[data-cy=search-skill]').type('php')
    cy.get('[data-cy=skill-already-in-list]').should('exist')
    cy.get('[data-cy=cancel-add-skill').click()

    // go to second employee and add the same skill
    cy.visit('/1/employees/2')
    cy.get('[data-cy=manage-skill-button]').click()
    cy.get('[data-cy=search-skill]').type('php')
    cy.get('[data-cy=skill-name-1]').click()
    cy.get('[data-cy=existing-skills-list-item-1]').should('exist')
    cy.get('[data-cy=cancel-add-skill]').click()
    cy.get('[data-cy=non-edit-skill-list-item-1]').contains('php')

    // now remove the php skill from the first employee
    cy.visit('/1/employees/1')
    cy.get('[data-cy=manage-skill-button]').click()
    cy.get('[data-cy=existing-skills-list-item-remove-1]').click()
    cy.get('[data-cy=cancel-add-skill').click()
    cy.get('[data-cy=skill-list-blank').should('exist')
  })

  it('should let a normal user manage a skill on his own employee profile page', function () {
    cy.login()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)

    cy.changePermission(1, 300)

    cy.visit('/1/employees/1')

    // create first skill
    cy.get('[data-cy=manage-skill-button]').click()
    cy.get('[data-cy=search-skill]').type('php')
    cy.get('[data-cy=submit-create-skill]').click()
    cy.get('[data-cy=existing-skills-list-item-1]').should('exist')
    cy.get('[data-cy=cancel-add-skill]').click()
    cy.get('[data-cy=non-edit-skill-list-item-1]').contains('php')

    // create skill with same name as above
    // it shouldn't let me create the skill
    cy.get('[data-cy=manage-skill-button]').click()
    cy.get('[data-cy=search-skill]').type('php')
    cy.get('[data-cy=skill-already-in-list]').should('exist')
    cy.get('[data-cy=cancel-add-skill').click()

    // now remove the php skill from the first employee
    cy.visit('/1/employees/1')
    cy.get('[data-cy=manage-skill-button]').click()
    cy.get('[data-cy=existing-skills-list-item-remove-1]').click()
    cy.get('[data-cy=cancel-add-skill').click()
    cy.get('[data-cy=skill-list-blank').should('exist')
  })

  it('should not let a normal user view someone elses skills', function () {
    cy.login()

    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)

    cy.changePermission(1, 300)

    cy.visit('/1/employees/2')
    cy.get('[data-cy=manage-skill-button]').should('not.exist')
  })
})
